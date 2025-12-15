<?php

declare(strict_types=1);

namespace CakeDto\Generator;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use PhpCollective\Dto\Engine\EngineInterface;
use PhpCollective\Dto\Generator\Builder as BaseBuilder;

/**
 * CakePHP-specific DTO Builder.
 *
 * Extends the base Builder from php-collective/dto with CakePHP-specific
 * configuration handling and namespace resolution.
 */
class Builder extends BaseBuilder {

	use InstanceConfigTrait {
		setConfig as traitSetConfig;
	}

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [];

	/**
	 * @param \PhpCollective\Dto\Engine\EngineInterface $engine
	 */
	public function __construct(EngineInterface $engine) {
		$config = new CakeConfig();
		parent::__construct($engine, $config);

		// Sync the config for InstanceConfigTrait compatibility
		$this->_defaultConfig = $this->config;
	}

	/**
	 * Set configuration values.
	 *
	 * Also syncs to parent's config array for compatibility.
	 *
	 * @param array<string, mixed>|string $key The key to set, or array of key/value pairs.
	 * @param mixed $value Value to set (if $key is a string).
	 * @param bool $merge Whether to merge or overwrite existing config.
	 *
	 * @return $this
	 */
	public function setConfig(array|string $key, mixed $value = null, bool $merge = true) {
		$this->traitSetConfig($key, $value, $merge);

		// Sync to parent's config array
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->config[$k] = $v;
			}
		} else {
			$this->config[$key] = $value;
		}

		return $this;
	}

	/**
	 * Get the namespace for the DTOs.
	 *
	 * CakePHP-specific: Falls back to App.namespace from Configure.
	 *
	 * @param string|null $namespace
	 * @param string|null $plugin
	 *
	 * @return string
	 */
	protected function _getNamespace(?string $namespace, ?string $plugin): string {
		if ($namespace) {
			return $namespace;
		}
		if ($plugin) {
			return str_replace('/', '\\', $plugin);
		}

		return Configure::read('App.namespace', 'App');
	}

}
