<?php

namespace CakeDto\Importer;

use CakeDto\Importer\Builder\SchemaBuilder;
use CakeDto\Importer\Parser\Data;
use CakeDto\Importer\Parser\Schema;

class Importer {

	/**
	 * @param string $json
	 * @param array<string, mixed> $options
	 *
	 * @return array
	 */
	public function parse(string $json, array $options = []): array {
		$array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
		if (!$array) {
			return [];
		}

		if (!$options['type']) {
			$options['type'] = $this->guessType($array);
		}

		return ParserFactory::create($options['type'])->parse($array, $options)->result();
	}

	/**
	 * @param array<string, mixed> $definitions
	 * @param array<string, mixed> $options
	 *
	 * @return array
	 */
	public function buildSchema(array $definitions, array $options = []): array {
		$result = [];
		foreach ($definitions as $name => $definition) {
			if (isset($definition['_include']) && !$definition['_include']) {
				continue;
			}
			unset($definition['_include']);
			$result[$name] = (new SchemaBuilder())->build($name, $definition, $options);
		}

		return $result;
	}

	/**
	 * @param array<string, mixed> $array
	 *
	 * @return string
	 */
	protected function guessType(array $array): string {
		if (!empty($array['type']) && $array['type'] === 'object' && !empty($array['properties'])) {
			return Schema::NAME;
		}

		return Data::NAME;
	}

}
