<?php

namespace CakeDto\Importer\Parser;

interface ParserInterface {

	/**
	 * Translates JSON input into DTO content.
	 *
	 * @param array<string, mixed> $input
	 * @param array<string, mixed> $options
	 *
	 * @return $this
	 */
	public function parse(array $input, array $options = []);

	/**
	 * @return array
	 */
	public function result(): array;

}
