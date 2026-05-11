<?php

namespace CakeDto\Importer;

use Cake\Core\Configure;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Inflector;

class DatabaseParser {

	/**
	 * Defaults are precision-safe and dependency-aware:
	 *
	 * - `decimal` maps to `string` instead of `float`. Mapping to `float`
	 *   reintroduces the precision loss that decimal columns exist to
	 *   prevent. This is an intentional generation-output BC break in a
	 *   bugfix release line: the previous `float` default was convenient,
	 *   but wrong for exact decimal storage. We don't default to a value-object like
	 *   `\PhpCollective\DecimalObject\Decimal` because cakephp-dto does
	 *   not require that package — generating a DTO referencing a class
	 *   that may not be installed is a worse failure mode than the
	 *   precision-safe `string` fallback. Cake's ORM already returns
	 *   decimal columns as strings by default, so this aligns with what
	 *   the entity layer hands you. Apps using cakephp-decimal can opt in
	 *   via the Configure override below.
	 * - `json` stays `array` for backward compatibility in the current major/minor
	 *   line. Applications that need schema-honest typing for scalar/object JSON
	 *   payloads can override this to `mixed` (or a project-specific DTO/value
	 *   object type) via Configure.
	 *
	 * Apps that prefer different shapes override entry-by-entry via
	 * `Configure::write('CakeDto.databaseTypeMap', [...])`, e.g.:
	 *
	 * ```php
	 * Configure::write('CakeDto.databaseTypeMap', [
	 *     'decimal' => 'float', // restore pre-fix behavior
	 *     'json' => 'mixed', // opt into schema-honest JSON typing
	 *     // or: 'decimal' => '\PhpCollective\DecimalObject\Decimal', // for cakephp-decimal users
	 * ]);
	 * ```
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
		'decimal' => 'string',
		'date' => '\Cake\I18n\Date',
		'datetime' => '\Cake\I18n\DateTime',
		'timestamp' => '\Cake\I18n\DateTime',
		'datetimefractional' => '\Cake\I18n\DateTime',
		'timestampfractional' => '\Cake\I18n\DateTime',
		'timestamptimezone' => '\Cake\I18n\DateTime',
		'time' => '\Cake\I18n\Time',
		'json' => 'array',
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
