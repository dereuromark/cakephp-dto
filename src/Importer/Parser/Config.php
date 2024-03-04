<?php

namespace CakeDto\Importer\Parser;

use Cake\Core\Configure;

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

	/**
	 * @return string[]
	 */
	public static function keyFields(): array {
		$defaults = [
			'slug',
			'login',
			'name',
		];

		return Configure::read('CakeDto.assocKeyFields') ?: $defaults;
	}

}
