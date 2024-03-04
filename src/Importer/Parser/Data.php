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
	protected array $result;

	/**
	 * @var array<string, array<string, string>>
	 */
	protected array $map;

	/**
	 * @param array $input
	 * @param array<string, mixed> $options
	 * @param array<string, string> $parentData
	 *
	 * @return $this
	 */
	public function parse(array $input, array $options = [], array $parentData = []) {
		$dtoName = $parentData ? ucfirst($parentData['field']) : 'Object';

		if ($options['namespace']) {
			$dtoName = rtrim($options['namespace'], '/') . '/' . $dtoName;
		}

		$fields = [];

		foreach ($input as $fieldName => $value) {
			if (str_starts_with($fieldName, '_')) {
				continue;
			}

			$fieldName = Inflector::variable($fieldName);

			if (is_array($value)) {
				if ($this->isAssoc($value)) {
					$data = [
						'dto' => $dtoName,
						'field' => $fieldName,
					];
					$this->parse($value, $options, $data);
				} else {
					//TODO
				}
			}

			$type = $this->type($value);

			$fieldDetails = [
				'type' => $type,
			];

			if (isset($this->map[$dtoName][$fieldName])) {
				$fieldDetails['type'] = $this->map[$dtoName][$fieldName];
			}

			$fields[$fieldName] = $fieldDetails;
		}

		$this->result[$dtoName] = $fields;
		if ($parentData) {
			$this->map[$parentData['dto']][$parentData['field']] = $dtoName;
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

}
