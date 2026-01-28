<?php

namespace CakeDto\Importer;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Inflector;

class DatabaseParser {

	/**
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
		'decimal' => 'float',
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
