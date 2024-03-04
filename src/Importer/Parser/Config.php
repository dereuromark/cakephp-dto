<?php

namespace CakeDto\Importer\Parser;

class Config {

	/**
	 * @return string[]
	 */
	public static function typeLabels(): array {
		return [
			Data::NAME => 'From JSON Data Example',
			Schema::NAME => 'From JSON Schema File',
		];
	}

	/**
	 * @return string[]
	 */
	public static function types(): array {
		return [
			Data::NAME => Data::class,
			Schema::NAME => Schema::class,
		];
	}

}
