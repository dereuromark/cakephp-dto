<?php

namespace CakeDto\Engine;

use InvalidArgumentException;
use Nette\Neon\Exception;
use Nette\Neon\Neon;

class NeonEngine implements EngineInterface {

	public const EXT = 'neon';

	/**
	 * @return string
	 */
	public function extension(): string {
		return static::EXT;
	}

	/**
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
		$result = [];

		try {
			$result = Neon::decode($content);
		} catch (Exception $e) {
			throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
		}

		if ($result === null) {
			throw new InvalidArgumentException(sprintf('%s: invalid neon content.', static::class));
		}

		foreach ($result as $name => $dto) {
			$dto['name'] = $name;

			$fields = isset($dto['fields']) ? $dto['fields'] : [];
			foreach ($fields as $key => $field) {
				if (is_string($field)) {
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
