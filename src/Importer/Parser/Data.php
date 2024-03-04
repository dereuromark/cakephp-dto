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
			if (is_array($value)) {

			}
		}

		return $this;
	}

	public function result(): array {
		// TODO: Implement result() method.
		return [];
	}

}
