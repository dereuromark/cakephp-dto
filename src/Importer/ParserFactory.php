<?php

namespace CakeDto\Importer;

use CakeDto\Importer\Parser\Config;
use CakeDto\Importer\Parser\ParserInterface;

class ParserFactory {

	/**
	 * @param string $type
	 *
	 * @return \CakeDto\Importer\Parser\ParserInterface
	 */
	public static function create(string $type): ParserInterface {
		$types = Config::types();
		/** @var class-string<\CakeDto\Importer\Parser\ParserInterface> $class */
		$class = $types[$type] ?? null;

		return new $class();
	}

}
