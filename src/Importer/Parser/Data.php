<?php

namespace CakeDto\Importer\Parser;

use Cake\Utility\Inflector;

class Data implements ParserInterface {

	/**
	 * @var string
	 */
	public const NAME = 'Data';

	/**
	 * @var array
	 */
	protected array $result = [];

	/**
	 * @var array<string, array<string, string>>
	 */
	protected array $map = [];

	/**
	 * @param array<string, mixed> $input
	 * @param array<string, mixed> $options
	 * @param array<string, mixed> $parentData
	 *
	 * @return $this
	 */
	public function parse(array $input, array $options = [], array $parentData = []) {
		$dtoName = 'Object';
		if ($parentData) {
			$field = $parentData['field'];
			if (!empty($parentData['collection'])) {
				$field = Inflector::singularize($field);
			}
			$dtoName = ucfirst($field);
		}

		if (!empty($options['namespace'])) {
			$dtoName = rtrim($options['namespace'], '/') . '/' . $dtoName;
		}

		$fields = [];

		foreach ($input as $fieldName => $value) {
			$fieldDetails = [
				'value' => $value,
			];
			if (str_starts_with($fieldName, '_')) {
				continue;
			}

			$fieldName = Inflector::variable($fieldName);

			if (is_array($value)) {
				if ($this->isAssoc($value)) {
					$parseDetails = ['dto' => $dtoName, 'field' => $fieldName];
					$this->parse($value, $options, $parseDetails);
				} elseif ($this->isNumericKeyed($value) && $this->hasAssocValues($value)) {
					$parseDetails = ['dto' => $dtoName, 'field' => $fieldName];
					$parseDetails['collection'] = true;

					$this->parse($value[0], $options, $parseDetails);
					$fieldDetails['collection'] = true;
				}
			}

			$type = $this->type($value);
			if (!empty($fieldDetails['collection'])) {
				$type = 'object';
			}

			$fieldDetails['type'] = $type;

			if (isset($this->map[$dtoName][$fieldName])) {
				$fieldDetails['type'] = $this->map[$dtoName][$fieldName];
				if (!empty($fieldDetails['collection'])) {
					$singular = Inflector::singularize($fieldName);
					// Skip on conflicting/existing field
					if (!isset($this->map[$dtoName][$singular])) {
						$fieldDetails['singular'] = $singular;
					}

					$keyField = $this->detectKeyField($value[0] ?? []);
					if ($keyField) {
						$fieldDetails['associative'] = $keyField;
					}
					$fieldDetails['type'] .= '[]';
				}
			}

			$fields[$fieldName] = $fieldDetails;
		}

		$this->result[$dtoName] = $fields;
		if ($parentData) {
			/** @var string $parentDtoName */
			$parentDtoName = $parentData['dto'];
			/** @var string $parentFieldName */
			$parentFieldName = $parentData['field'];
			$this->map[$parentDtoName][$parentFieldName] = $dtoName;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function result(): array {
		return $this->result;
	}

	/**
	 * @param mixed $value
	 *
	 * @return string|null
	 */
	protected function type(mixed $value): ?string {
		$type = gettype($value);

		if ($type === 'NULL') {
			return 'mixed';
		}

		return $this->normalize($type);
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	protected function normalize(string $name): string {
		return match ($name) {
			'boolean' => 'bool',
			'real', 'double' => 'float',
			'integer' => 'int',
			'[]' => 'array',
			default => $name,
		};
	}

	/**
	 * @param array $value
	 *
	 * @return bool
	 */
	protected function isAssoc(array $value): bool {
		foreach ($value as $k => $v) {
			if (!is_string($k)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array $value
	 *
	 * @return bool
	 */
	protected function isNumericKeyed(array $value): bool {
		foreach ($value as $k => $v) {
			if (!is_int($k)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array $value
	 *
	 * @return bool
	 */
	protected function hasAssocValues(array $value): bool {
		foreach ($value as $k => $v) {
			if (!is_array($v) || !$this->isAssoc($v)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array<string, mixed> $value
	 *
	 * @return string|null
	 */
	protected function detectKeyField(array $value): ?string {
		$strings = Config::keyFields();
		foreach ($strings as $string) {
			if (isset($value[$string]) && is_string($value[$string])) {
				return $string;
			}
		}

		return null;
	}

}
