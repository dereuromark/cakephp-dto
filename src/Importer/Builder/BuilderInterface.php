<?php

namespace CakeDto\Importer\Builder;

interface BuilderInterface {

	/**
	 * Translates array definition into DTO schema or code.
	 *
	 * @param string $name
	 * @param array<string, mixed> $input
	 *
	 * @return string
	 */
	public function build(string $name, array $input): string;

}
