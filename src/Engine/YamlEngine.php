<?php

namespace CakeDto\Engine;

use InvalidArgumentException;

class YamlEngine implements EngineInterface {

	public const EXT = 'yml';

	/**
	 * @return string
	 */
	public function extension(): string {
		return static::EXT;
	}

	/**
	 * Validates files.
	 *
	 * @param array $files
	 * @return void
	 */
	public function validate(array $files): void {
	}

	/**
	 * Parses content into array form. Can also already contain basic validation
	 * if validate() cannot be used.
	 *
	 * @param string $content
	 *
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	public function parse(string $content): array {
		$result = yaml_parse($content);
		if (!$result) {
			throw new InvalidArgumentException('Invalid YAML file');
		}

		foreach ($result as $name => $dto) {
			$dto['name'] = $name;

			$fields = isset($dto['fields']) ? $dto['fields'] : [];
			foreach ($fields as $key => $field) {
				if (!is_array($field)) {
					$field = [
						'type' => $field,
					];
				}
				$field['name'] = $key;
				$fields[$key] = $field;
			}

			$dto['fields'] = $fields;

			$result[$name] = $dto;
		}

		return $result;
	}

}
