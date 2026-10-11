<?php

declare(strict_types=1);

namespace CakeDto\Generator;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use InvalidArgumentException;
use PhpCollective\Dto\Engine\EngineInterface;
use PhpCollective\Dto\Generator\Builder as BaseBuilder;

/**
 * CakePHP-specific DTO Builder.
 *
 * Extends the base Builder from php-collective/dto with CakePHP-specific
 * configuration handling and namespace resolution.
 */
class Builder extends BaseBuilder {

	/**
	 * @param \PhpCollective\Dto\Engine\EngineInterface $engine
	 */
	public function __construct(EngineInterface $engine) {
		parent::__construct($engine, new CakeConfig());
	}

	/**
	 * @param array<string, mixed>|string $key
	 * @param mixed $value
	 * @param bool $merge
	 * @return static
	 */
	public function setConfig(array|string $key, mixed $value = null, bool $merge = true): static {
		if (is_string($key) && $value === null) {
			return $this->deleteConfig($key);
		}
		$values = is_array($key) ? $key : [$key => $value];
		if ($merge) {
			$this->config = Hash::merge($this->config, Hash::expand($values));
		} else {
			foreach ($values as $path => $setting) {
				$this->config = Hash::insert($this->config, $path, $setting);
			}
		}

		return $this;
	}

	/**
	 * @param string|null $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getConfig(?string $key = null, mixed $default = null): mixed {
		return $key === null ? $this->config : Hash::get($this->config, $key, $default);
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getConfigOrFail(string $key): mixed {
		$value = $this->getConfig($key);
		if ($value === null) {
			throw new InvalidArgumentException(sprintf('Expected configuration `%s` not found.', $key));
		}

		return $value;
	}

	/**
	 * @param array<string, mixed>|string $key
	 * @param mixed $value
	 * @return static
	 */
	public function configShallow(array|string $key, mixed $value = null): static {
		$this->config = array_merge($this->config, Hash::expand(is_array($key) ? $key : [$key => $value]));

		return $this;
	}

	/**
	 * @param string $key
	 * @return static
	 */
	public function deleteConfig(string $key): static {
		$this->config = Hash::remove($this->config, $key);

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
