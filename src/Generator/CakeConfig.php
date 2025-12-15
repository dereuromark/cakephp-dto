<?php

declare(strict_types=1);

namespace CakeDto\Generator;

use Cake\Core\Configure;
use PhpCollective\Dto\Generator\ConfigInterface;
use PhpCollective\Dto\Generator\Finder;

/**
 * CakePHP-specific configuration adapter.
 *
 * Reads configuration from CakePHP's Configure class and provides
 * it via the ConfigInterface for the base Builder.
 */
class CakeConfig implements ConfigInterface {

	/**
	 * @var array<string, mixed>
	 */
	protected array $config;

	/**
	 * Constructor - reads configuration from CakePHP's Configure.
	 */
	public function __construct() {
		$this->config = [
			'scalarAndReturnTypes' => Configure::read('CakeDto.scalarAndReturnTypes', true),
			'typedConstants' => Configure::read('CakeDto.typedConstants', false),
			'defaultCollectionType' => Configure::read('CakeDto.defaultCollectionType', '\ArrayObject'),
			'debug' => (bool)Configure::read('CakeDto.debug'),
			'immutable' => (bool)Configure::read('CakeDto.immutable'),
			'finder' => Configure::read('CakeDto.finder', Finder::class),
			'suffix' => Configure::read('CakeDto.suffix', 'Dto'),
			'namespace' => Configure::read('App.namespace', 'App'),
		];
	}

	/**
	 * Get a configuration value.
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get(string $key, mixed $default = null): mixed {
		return $this->config[$key] ?? $default;
	}

	/**
	 * Get all configuration values.
	 *
	 * @return array<string, mixed>
	 */
	public function all(): array {
		return $this->config;
	}

}
