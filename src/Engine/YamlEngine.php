<?php
namespace Dto\Engine;

use InvalidArgumentException;

class YamlEngine implements EngineInterface {

	const EXT = 'yml';

	/**
	 * @return string
	 */
	public function extension() {
		return static::EXT;
	}

	/**
	 * Validates files.
	 *
	 * @param array $files
	 * @return void
	 */
	public function validate(array $files) {
	}

	/**
	 * Parses content into array form. Can also already contain basic validation
	 * if validate() cannot be used.
	 *
	 * @param string $content
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function parse($content) {
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
