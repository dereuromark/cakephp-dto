<?php

namespace CakeDto\Importer\Parser;

use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class Schema implements ParserInterface {

	/**
	 * @var string
	 */
	public const NAME = 'Schema';

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
	 * @param array<string, mixed> $parentData When nesting
	 *
	 * @return $this
	 */
	public function parse(array $input, array $options = [], array $parentData = []) {
		if (!$input || empty($input['properties'])) {
			return $this;
		}

		$dtoName = !empty($input['title']) ? Inflector::camelize($input['title']) : null;
		if (!$dtoName) {
			$field = $parentData['field'];
			if (!empty($parentData['collection'])) {
				$field = Inflector::singularize($field);
			}
			$dtoName = $parentData ? ucfirst($field) : 'Object';
		}
		if ($options['namespace']) {
			$dtoName = rtrim($options['namespace'], '/') . '/' . $dtoName;
		}

		$fields = [];

		foreach ($input['properties'] as $fieldName => $details) {
			if (!$details || str_starts_with($fieldName, '_') || !empty($details['$ref'])) {
				continue;
			}
			if (!is_array($details)) {
				continue;
			}

			$required = in_array($fieldName, $input['required'] ?? [], true);

			if (!isset($details['type']) && !empty($details['anyOf'])) {
				$details['type'] = $this->guessType($details['anyOf']);
				if (in_array('object', $details['type'])) {
					$details = $this->detailsFromObject($details);
				}
			}
			if (!isset($details['type']) && !empty($details['oneOf'])) {
				$details['type'] = $this->guessType($details['oneOf']);
				if (in_array('object', $details['type'])) {
					$details = $this->detailsFromObject($details);
				}
			}
			if (!isset($details['type']) && !empty($details['enum'])) {
				$details['type'] = 'string';
			}

			if (is_array($details['type']) && in_array('array', $details['type'])) {
				$details['type'] = 'array';
				$required = false;
			}

			if ($details['type'] === 'array' && !empty($details['items']['type']) && $details['items']['type'] === 'object') {
				$details['collection'] = true;
				$details['type'] = 'object';
				$details['properties'] = $details['items']['properties'];
				$details['required'] = $details['items']['required'] ?? null;
			}

			if (!empty($details['type']) && !is_string($details['type'])) {
				if (in_array('null', $details['type'], true)) {
					$keys = array_keys($details['type'], 'null');
					foreach ($keys as $key) {
						unset($details['type'][$key]);
					}
					$required = false;
				}

				$details['type'] = implode('|', $details['type']);
			}

			if (!isset($details['type'])) {
				$details['type'] = 'mixed';
			}
			if (empty($details['type'])) {
				continue;
			}

			$fieldName = Inflector::variable($fieldName);

			$fieldDetails = [
				'type' => $this->type($details['type']),
				'required' => $required,
			];

			if ($fieldDetails['type'] === 'object') {
				$parseDetails = ['dto' => $dtoName, 'field' => $fieldName];
				if (!empty($details['collection'])) {
					$parseDetails['collection'] = $details['collection'];
				}
				$this->parse($details, $options, $parseDetails);

				if (isset($this->map[$dtoName][$fieldName])) {
					$fieldDetails['type'] = $this->map[$dtoName][$fieldName];
					if (!empty($details['collection'])) {
						$singular = Inflector::singularize($fieldName);
						// Skip on conflicting/existing field
						if (!isset($this->map[$dtoName][$singular])) {
							$fieldDetails['singular'] = $singular;
						}
						$dtoFields = $details['items']['properties'] ?? [];
						$keyField = $this->detectKeyField($dtoFields);
						if ($keyField) {
							$fieldDetails['associative'] = $keyField;
						}
						$fieldDetails['type'] .= '[]';
					}
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
	 * @param string $type
	 *
	 * @return string
	 */
	protected function type(string $type): string {
		$type = $this->normalize($type);

		return $type;
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
	 * @param array $anyOf
	 *
	 * @return list<string>
	 */
	protected function guessType(array $anyOf): array {
		return Hash::extract($anyOf, '{n}.type');
	}

	/**
	 * @param array<string, mixed> $details
	 *
	 * @return array<string, mixed>
	 */
	protected function detailsFromObject(array $details): array {
		if (empty($details['anyOf'])) {
			return $details;
		}

		foreach ($details['anyOf'] as $item) {
			if (empty($item['type'])) {
				continue;
			}

			if ($item['type'] !== 'object') {
				continue;
			}

			$details['properties'] = $item['properties'];
			$details['required'] = $item['required'] ?? null;
			$details['title'] = $item['title'];
		}

		return $details;
	}

	/**
	 * @param array<string, mixed> $dtoFields
	 *
	 * @return string|null
	 */
	protected function detectKeyField(array $dtoFields): ?string {
		$strings = Config::keyFields();
		foreach ($strings as $string) {
			if (!empty($dtoFields[$string]) && $dtoFields[$string]['type'] === 'string') {
				return $string;
			}
		}

		return null;
	}

}
