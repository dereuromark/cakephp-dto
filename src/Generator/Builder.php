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

	/**
	 * Create DTOs from config, adding arrayShape for each.
	 *
	 * Overrides parent to add arrayShape for shaped array types in toArray()/createFromArray().
	 * This is needed until php-collective/dto releases a version with this feature.
	 *
	 * @param array<string, array<string, mixed>> $config
	 * @param string $namespace
	 *
	 * @return array<string, array<string, mixed>>
	 */
	protected function _createDtos(array $config, string $namespace): array {
		$config = parent::_createDtos($config, $namespace);

		// Add shaped array types for toArray()/createFromArray() PHPDoc
		foreach ($config as $name => $dto) {
			$config[$name]['arrayShape'] = $this->buildArrayShape($dto['fields'], $config);
		}

		return $config;
	}

	/**
	 * Build PHPStan shaped array type for a DTO's fields.
	 *
	 * This method is included here until php-collective/dto releases a version
	 * with this feature.
	 *
	 * @param array<string, array<string, mixed>> $fields
	 * @param array<string, array<string, mixed>> $allDtos
	 *
	 * @return string
	 */
	protected function buildArrayShape(array $fields, array $allDtos = []): string {
		$parts = [];

		foreach ($fields as $name => $field) {
			$type = $this->buildFieldShapeType($field, $allDtos);
			$parts[] = $name . ': ' . $type;
		}

		return 'array{' . implode(', ', $parts) . '}';
	}

	/**
	 * Build the shaped array type for a single field.
	 *
	 * @param array<string, mixed> $field
	 * @param array<string, array<string, mixed>> $allDtos
	 *
	 * @return string
	 */
	protected function buildFieldShapeType(array $field, array $allDtos = []): string {
		// For collections, use array<keyType, elementType>
		if (!empty($field['collection']) || !empty($field['isArray'])) {
			$elementType = $field['singularType'] ?? 'mixed';
			$keyType = !empty($field['associative']) ? 'string' : 'int';

			// If element is a DTO, try to resolve its shape
			$dtoName = $this->extractDtoName($elementType);
			if ($dtoName && isset($allDtos[$dtoName])) {
				$nestedShape = $this->buildArrayShape($allDtos[$dtoName]['fields'], $allDtos);
				$elementType = $nestedShape;
			}

			$type = sprintf('array<%s, %s>', $keyType, $elementType);
		} elseif (!empty($field['dto'])) {
			// For nested DTOs, build nested shape if available
			$dtoName = $this->extractDtoName($field['type']);
			if ($dtoName && isset($allDtos[$dtoName])) {
				$type = $this->buildArrayShape($allDtos[$dtoName]['fields'], $allDtos);
			} else {
				$type = 'array<string, mixed>';
			}
		} else {
			// Simple type
			$type = $field['typeHint'] ?? $field['type'] ?? 'mixed';
		}

		// Add null if nullable
		if (!empty($field['nullable'])) {
			$type .= '|null';
		}

		return $type;
	}

	/**
	 * Extract the DTO name from a fully qualified class name.
	 *
	 * @param string $type
	 *
	 * @return string|null
	 */
	protected function extractDtoName(string $type): ?string {
		// Remove leading backslash and namespace, extract class name without Dto suffix
		$className = ltrim($type, '\\');
		$parts = explode('\\', $className);
		$shortName = end($parts);

		// Remove Dto suffix if present
		$suffix = $this->getConfigOrFail('suffix');
		if (str_ends_with($shortName, $suffix)) {
			return substr($shortName, 0, -strlen($suffix));
		}

		return $shortName;
	}

}
