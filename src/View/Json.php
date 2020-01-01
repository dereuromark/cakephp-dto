<?php

namespace CakeDto\View;

use RuntimeException;

class Json {

	const DEFAULT_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR;
	const DEFAULT_DEPTH = 512;

	/**
	 * @param array $value
	 * @param int|null $options
	 * @param int|null $depth
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	public function encode(array $value, ?int $options = null, ?int $depth = null): string {
		if ($options === null) {
			$options = static::DEFAULT_OPTIONS;
		}

		if ($depth === null) {
			$depth = static::DEFAULT_DEPTH;
		}

		$value = json_encode($value, $options, $depth);

		if ($value === false) {
			throw new RuntimeException('JSON encoding failed.');
		}

		return $value;
	}

	/**
	 * @param string $jsonString
	 * @param bool $assoc
	 * @param int|null $depth
	 * @param int|null $options
	 *
	 * @return array|null
	 * @throws \RuntimeException
	 */
	public function decode(string $jsonString, bool $assoc = false, ?int $depth = null, ?int $options = null): ?array {
		if ($options === null) {
			$options = static::DEFAULT_OPTIONS;
		}

		if ($depth === null) {
			$depth = static::DEFAULT_DEPTH;
		}

		$array = json_decode($jsonString, $assoc, $depth, $options);

		if (!is_array($array)) {
			throw new RuntimeException('JSON decoding failed.');
		}

		return $array;
	}

}
