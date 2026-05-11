<?php

namespace CakeDto\Importer;

use Cake\Core\Configure;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Inflector;

class DatabaseParser {

	/**
	 * Defaults are now precision-and-shape-friendly:
	 *
	 * - `decimal` maps to `\PhpCollective\DecimalObject\Decimal` instead of
	 *   `float`. Mapping to `float` re-introduces the precision loss that
	 *   cakephp-decimal (a common Cake companion plugin) exists to solve.
	 * - `json` maps to `mixed` instead of `array`. A JSON column may hold a
	 *   single object, a scalar, or an array, and forcing `array` strips
	 *   that information at the type level. `mixed` lets the generated DTO
	 *   round-trip whatever the column actually contains.
	 *
	 * Apps that prefer the old `float`/`array` behavior — or that don't use
	 * cakephp-decimal — can override the map entry-by-entry with
	 * `Configure::write('CakeDto.databaseTypeMap', ['decimal' => 'float', 'json' => 'array'])`,
	 * or per-entry shorter forms.
	 *
	 * @var array<string, string>
	 */
	protected array $typeMap = [
		'integer' => 'int',
		'biginteger' => 'int',
		'smallinteger' => 'int',
		'tinyinteger' => 'int',
		'string' => 'string',
		'text' => 'string',
		'char' => 'string',
		'uuid' => 'string',
		'boolean' => 'bool',
		'float' => 'float',
		'decimal' => '\PhpCollective\DecimalObject\Decimal',
		'date' => '\Cake\I18n\Date',
		'datetime' => '\Cake\I18n\DateTime',
		'timestamp' => '\Cake\I18n\DateTime',
		'datetimefractional' => '\Cake\I18n\DateTime',
		'timestampfractional' => '\Cake\I18n\DateTime',
		'timestamptimezone' => '\Cake\I18n\DateTime',
		'time' => '\Cake\I18n\Time',
		'json' => 'mixed',
		'binary' => 'string',
	];

	public function __construct() {
		// Allow opt-in overrides without subclassing.
		$overrides = Configure::read('CakeDto.databaseTypeMap');
		if (is_array($overrides)) {
			foreach ($overrides as $cakeType => $dtoType) {
				if (is_string($cakeType) && is_string($dtoType)) {
					$this->typeMap[$cakeType] = $dtoType;
				}
			}
		}
	}

	/**
	 * @param string $connectionName
	 *
	 * @return array<string>
	 */
	public function listTables(string $connectionName = 'default'): array {
		$connection = ConnectionManager::get($connectionName);
		/** @var \Cake\Database\Connection $connection */
		$schemaCollection = $connection->getSchemaCollection();

		return $schemaCollection->listTables();
	}

	/**
	 * @param array<string> $tables
	 * @param string $connectionName
	 *
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function parse(array $tables, string $connectionName = 'default'): array {
		$connection = ConnectionManager::get($connectionName);
		/** @var \Cake\Database\Connection $connection */
		$schemaCollection = $connection->getSchemaCollection();

		$result = [];
		foreach ($tables as $table) {
			$tableSchema = $schemaCollection->describe($table);
			$dtoName = $this->tableToDtoName($table);
			$result[$dtoName] = $this->parseTable($tableSchema);
		}

		return $result;
	}

	/**
	 * @param \Cake\Database\Schema\TableSchemaInterface $tableSchema
	 *
	 * @return array<string, array<string, mixed>>
	 */
	protected function parseTable(TableSchemaInterface $tableSchema): array {
		$fields = [];
		$primaryKey = $tableSchema->getPrimaryKey();

		foreach ($tableSchema->columns() as $column) {
			$columnData = $tableSchema->getColumn($column);
			if (!$columnData) {
				continue;
			}

			$fieldName = Inflector::variable($column);
			$type = $columnData['type'] ?? 'string';
			$dtoType = $this->typeMap[$type] ?? 'string';

			$field = [
				'type' => $dtoType,
			];

			$isNullable = $columnData['null'] ?? false;
			$hasDefault = array_key_exists('default', $columnData) && $columnData['default'] !== null;
			$isAutoIncrement = !empty($columnData['autoIncrement']);
			$isPrimaryKey = in_array($column, $primaryKey, true);

			if (!$isNullable && !$hasDefault && !$isAutoIncrement && !$isPrimaryKey) {
				$field['required'] = true;
			}

			$fields[$fieldName] = $field;
		}

		return $fields;
	}

	/**
	 * @param string $tableName
	 *
	 * @return string
	 */
	protected function tableToDtoName(string $tableName): string {
		return Inflector::camelize(Inflector::singularize($tableName));
	}

}
