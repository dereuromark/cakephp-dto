<?php

namespace CakeDto\Generator;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Utility\Inflector;
use CakeDto\Dto\FromArrayToArrayInterface;
use CakeDto\Engine\EngineInterface;
use InvalidArgumentException;
use RuntimeException;

class Builder {

	use InstanceConfigTrait;

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
		'finder' => Finder::class,
	];

	/**
	 * @var \CakeDto\Engine\EngineInterface
	 */
	protected $engine;

	/**
	 * PHP 7.1 will add 'iterable', PHP 7.2 will add 'object'
	 *
	 * @var array
	 */
	protected $simpleTypeWhitelist = [
		'int',
		'float',
		'string',
		'bool',
		'callable',
	];

	/**
	 * @var array
	 */
	protected $simpleTypeAdditionsForDocBlock = [
		'resource',
		'mixed', // Not for [] array notation
	];

	/**
	 * Needed for for Dto to work dynamically.
	 *
	 * @var array
	 */
	protected $metaDataKeys = [
		'name',
		'type',
		'isClass',
		'serializable',
		'required',
		'defaultValue',
		'dto',
		'toArray',
		'collectionType',
		'singularType',
		'singularTypeHint',
		'singularNullable',
		'associative',
		'key',
	];

	/**
	 * @param \CakeDto\Engine\EngineInterface $engine
	 */
	public function __construct(EngineInterface $engine) {
		$this->engine = $engine;

		$this->simpleTypeWhitelist = $this->simpleTypeWhitelist($this->simpleTypeWhitelist);
		$config = [
			'scalarTypeHints' => Configure::read('CakeDto.scalarTypeHints', true),
			'defaultCollectionType' => Configure::read('CakeDto.defaultCollectionType', '\ArrayObject'),
			'debug' => (bool)Configure::read('CakeDto.debug'),
			'immutable' => (bool)Configure::read('CakeDto.immutable'),
		];
		$this->setConfig($config);
	}

	/**
	 * @param string $configPath
	 * @param array $options
	 * @return array
	 */
	public function build(string $configPath, array $options = []): array {
		$options += [
			'plugin' => null,
			'namespace' => null,
		];
		$namespace = $this->_getNamespace($options['namespace'], $options['plugin']);

		$files = $this->_getFiles($configPath);

		$config = [];
		foreach ($files as $file) {
			$content = file_get_contents($file) ?: '';
			$config[$file] = $this->engine->parse($content);
		}

		$result = $this->_merge($config);

		return $this->_createDtos($result, $namespace);
	}

	/**
	 * @param array $config
	 * @param string $namespace
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	protected function _createDtos(array $config, string $namespace): array {
		foreach ($config as $name => $dto) {
			$this->_validateDto($dto);
			$dto = $this->_complete($dto, $namespace);
			$dto = $this->_completeMeta($dto, $namespace);

			$dto += [
				'immutable' => $this->_config['immutable'],
				'namespace' => $namespace . '\Dto',
				'className' => $name . 'Dto',
				'extends' => '\\CakeDto\\Dto\\AbstractDto',
			];

			if (!empty($dto['immutable']) && $dto['extends'] === '\\CakeDto\\Dto\\AbstractDto') {
				$dto['extends'] = '\\CakeDto\\Dto\\AbstractImmutableDto';
			}

			$config[$name] = $dto;
		}

		foreach ($config as $name => $dto) {
			if (in_array($dto['extends'], ['\\CakeDto\\Dto\\AbstractDto', '\\CakeDto\\Dto\\AbstractImmutableDto'], true)) {
				continue;
			}

			$extendedDto = $dto['extends'];
			$config[$name]['extends'] = $extendedDto . 'Dto';

			if (!isset($config[$extendedDto])) {
				throw new InvalidArgumentException(sprintf('Invalid %s DTO attribute `extends`: `%s`. DTO does not seem to exist.', $dto['name'], $dto['extends']));
			}

			if (empty($dto['immutable']) && !empty($config[$extendedDto]['immutable'])) {
				throw new InvalidArgumentException(sprintf('Invalid %s DTO attribute `extends`: `%s`. Extended DTO is immutable.', $dto['name'], $dto['extends']));
			}
			if (!empty($dto['immutable']) && empty($config[$extendedDto]['immutable'])) {
				throw new InvalidArgumentException(sprintf('Invalid %s DTO attribute `extends`: `%s`. Extended DTO is not immutable.', $dto['name'], $dto['extends']));
			}

			$config[$name] += $this->_config;
		}

		foreach ($config as $name => $dto) {
			if (strpos($dto['className'], '/') !== false) {
				$pieces = explode('/', $dto['className']);
				$dto['className'] = array_pop($pieces);
				$dto['namespace'] .= '\\' . implode('\\', $pieces);
			}
			if (strpos($dto['extends'], '/') !== false) {
				$pieces = explode('/', $dto['extends']);
				$dto['extends'] = '\\' . $namespace . '\Dto\\' . implode('\\', $pieces);
			}

			$config[$name] = $dto;
		}

		return $config;
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	protected function isValidType(string $type): bool {
		if ($this->isValidSimpleType($type, $this->simpleTypeAdditionsForDocBlock)) {
			return true;
		}
		if ($this->isValidDto($type) || $this->isValidInterfaceOrClass($type)) {
			return true;
		}

		if ($this->isValidArray($type) || $this->isValidCollection($type)) {
			return true;
		}

		return false;
	}

	/**
	 * @param array $dto
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	protected function _validateDto(array $dto): void {
		if (empty($dto['name'])) {
			throw new InvalidArgumentException('DTO name missing, but required.');
		}
		$dtoName = $dto['name'];
		if (!$this->isValidDto($dtoName)) {
			throw new InvalidArgumentException(sprintf('Invalid DTO name `%s`.', $dtoName));
		}

		if (!empty($dto['extends']) && !$this->isValidDto($dto['extends'])) {
			throw new InvalidArgumentException(sprintf('Invalid %s DTO attribute `extends`: `%s`. Only DTOs are allowed.', $dtoName, $dto['extends']));
		}

		$fields = $dto['fields'];

		foreach ($fields as $name => $array) {
			if (empty($array['name'])) {
				throw new InvalidArgumentException(sprintf('Field field attribute `%s:name` in %s DTO missing, but required.', $name, $dtoName));
			}
			if (empty($array['type'])) {
				throw new InvalidArgumentException(sprintf('Field field attribute `%s:type` in %s DTO missing, but required.', $name, $dtoName));
			}
			foreach ($array as $key => $value) {
				$expected = Inflector::variable(Inflector::underscore($key));
				if ($key !== $expected) {
					throw new InvalidArgumentException(sprintf('Invalid field attribute `%s:%s` in %s DTO, expected `%s`', $name, $key, $dtoName, $expected));
				}
			}

			if (!$this->isValidName($array['name'])) {
				throw new InvalidArgumentException(sprintf('Invalid field attribute `name` in %s DTO: `%s`.', $name, $array['name']));
			}
			if (!$this->isValidType($array['type'])) {
				throw new InvalidArgumentException(sprintf('Invalid field attribute `%s:type` in %s DTO: `%s`.', $name, $dtoName, $array['type']));
			}

			if (!empty($array['singular'])) {
				$expected = Inflector::variable(Inflector::underscore($array['singular']));
				if ($array['singular'] !== $expected) {
					throw new InvalidArgumentException(sprintf('Invalid field attribute `%s:singular` in %s DTO, expected `%s`', $name, $dtoName, $expected));
				}

				if (isset($array['collection']) && $array['collection'] === false) {
					throw new InvalidArgumentException(sprintf('Invalid field attribute `%s:singular` in %s DTO, only collections can define this.', $name, $dtoName));
				}
			}
		}
	}

	/**
	 * @param array $configs
	 *
	 * @return array
	 */
	protected function _merge(array $configs): array {
		$result = [];
		foreach ($configs as $config) {
			$result += $config;

			foreach ($config as $name => $dto) {
				$this->validateMerge($result[$name], $dto);

				$result[$name] += $dto;
			}
		}

		return $result;
	}

	/**
	 * @param array $dto
	 * @param string $namespace
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	protected function _complete(array $dto, string $namespace): array {
		$fields = $dto['fields'];
		foreach ($fields as $field => $data) {
			$data += [
				'required' => isset($data['defaultValue']),
				'defaultValue' => null,
				'nullable' => empty($data['required']),
				'returnTypeHint' => null,
				'isArray' => false,
				'dto' => null,
				'collection' => !empty($data['singular']),
				'collectionType' => null,
				'associative' => false,
				'key' => null,
				'deprecated' => null,
				'serializable' => false,
				'toArray' => false,
			];
			if ($data['required']) {
				$data['nullable'] = false;
			}

			$fields[$field] = $data;
		}

		foreach ($fields as $key => $field) {
			if ($this->isValidSimpleType($field['type'], $this->simpleTypeAdditionsForDocBlock)) {
				continue;
			}
			if ($this->isValidDto($field['type'])) {
				$fields[$key]['dto'] = $field['type'];

				continue;
			}
			if ($this->isCollection($field)) {
				$fields[$key]['collection'] = true;
				$fields[$key]['collectionType'] = $this->collectionType($field);
				$fields[$key]['nullable'] = false;

				$fields[$key] = $this->_completeCollectionSingular($fields[$key], $dto['name'], $namespace);
				$fields[$key]['singularNullable'] = substr($fields[$key]['type'], 0, 1) === '?';

				if (preg_match('#^([A-Z][a-zA-Z/]+)\[\]$#', $field['type'], $matches)) {
					$fields[$key]['type'] = $this->dtoTypeToClass($matches[1], $namespace) . '[]';
				}

				if ($fields[$key]['singularNullable']) {
					$fields[$key]['type'] = '(' . $fields[$key]['singularType'] . '|null)[]';
				}

				continue;
			}
			if ($this->isValidArray($field['type'])) {
				$fields[$key]['isArray'] = true;
				if (preg_match('#^([A-Z][a-zA-Z/]+)\[\]$#', $field['type'], $matches)) {
					$fields[$key]['type'] = $this->dtoTypeToClass($matches[1], $namespace) . '[]';
				}

				continue;
			}

			if ($this->isValidInterfaceOrClass($field['type'])) {
				$fields[$key]['isClass'] = true;

				$serializable = is_subclass_of($fields[$key]['type'], FromArrayToArrayInterface::class);
				$fields[$key]['serializable'] = $serializable;
				if (!$serializable) {
					$fields[$key]['toArray'] = method_exists($fields[$key]['type'], 'toArray');
				}

				continue;
			}

			throw new InvalidArgumentException(sprintf('Invalid type `%s` for field `%s` in %s DTO', $field['type'], $key, $dto['name']));
		}

		$dto['fields'] = $fields;

		return $dto;
	}

	/**
	 * @param array $field
	 *
	 * @return bool
	 */
	protected function isCollection(array $field): bool {
		if (!$field['collection'] && !$field['collectionType'] && !$field['associative']) {
			return false;
		}

		return true;
	}

	/**
	 * @param array $data
	 * @param string $dtoName
	 * @param string $namespace
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	protected function _completeCollectionSingular(array $data, string $dtoName, string $namespace): array {
		$fieldName = $data['name'];
		if (!$data['collection'] && empty($data['collectionType'])) {
			return $data;
		}

		$data['singularType'] = $this->singularType($data['type']);
		if ($this->isValidDto($data['singularType'])) {
			$data['singularType'] = $this->dtoTypeToClass($data['singularType'], $namespace);
			$data['singularClass'] = $data['singularType'];
		}

		if (!empty($data['singular'])) {
			return $data;
		}

		if (preg_match('#^([A-Z][a-zA-Z/]+)\[\]$#', $data['type'], $matches)) {
			$singular = $matches[1];

			if (strpos($singular, '/') !== false) {
				$singular = substr($singular, strrpos($singular, '/') + 1);
			}

			$data['singular'] = lcfirst($singular);

			return $data;
		}

		$singular = Inflector::singularize($fieldName);
		if ($singular === $fieldName) {
			throw new InvalidArgumentException(sprintf('Field name `%s` of %s DTO cannot be singularized automatically, please set `singular` value.', $fieldName, $dtoName));
		}

		$data['singular'] = $singular;

		return $data;
	}

	/**
	 * @param array $dto
	 * @param string $namespace
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	protected function _completeMeta(array $dto, string $namespace): array {
		$fields = $dto['fields'];

		foreach ($fields as $key => $field) {
			if ($field['dto']) {
				$className = $this->dtoTypeToClass($field['type'], $namespace);
				$fields[$key]['type'] = $className;
				$fields[$key]['typeHint'] = $className;
			} else {
				$fields[$key]['typeHint'] = $field['type'];
			}
			$fields[$key]['typeHint'] = $this->typehint($fields[$key]['typeHint']);

			if ($field['collection']) {
				if ($field['collectionType'] === 'array') {
					$fields[$key]['typeHint'] = 'array';
				} else {
					$fields[$key]['typeHint'] = $field['collectionType'];

					$fields[$key]['type'] .= '|' . $fields[$key]['typeHint'];
				}
			}
			if ($field['isArray']) {
				if ($field['type'] !== 'array') {
					$fields[$key]['typeHint'] = 'array';
				}
			}

			if ($fields[$key]['typeHint'] && $this->_config['scalarTypeHints']) {
				$fields[$key]['returnTypeHint'] = $fields[$key]['typeHint'];
			}

			if ($fields[$key]['typeHint'] && $this->_config['scalarTypeHints'] && $fields[$key]['nullable']) {
				$fields[$key]['typeHint'] = '?' . $fields[$key]['typeHint'];
			}

			if ($fields[$key]['collection']) {
				$fields[$key] += [
					'singularTypeHint' => null,
					'singularNullable' => false,
					'singularReturnTypeHint' => null,
				];
				if ($fields[$key]['singularType']) {

					$fields[$key]['singularTypeHint'] = $this->typehint($fields[$key]['singularType']);
				}

				if ($fields[$key]['singularTypeHint'] && $this->_config['scalarTypeHints']) {
					$fields[$key]['singularReturnTypeHint'] = $fields[$key]['singularTypeHint'];
				}
			}
		}

		$dto['fields'] = $fields;
		$dto['metaData'] = $this->metaData($fields);

		$dto += [
			'deprecated' => null,
		];

		return $dto;
	}

	/**
	 * @param array $field
	 * @return string
	 */
	protected function collectionType(array $field): string {
		if ($field['collectionType']) {
			return $field['collectionType'];
		}
		if ($field['collection']) {
			return $this->_config['defaultCollectionType'];
		}

		return 'array';
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	protected function isValidName(string $name): bool
	{
		if (preg_match('#^[a-zA-Z]+$#', $name)) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	protected function isValidDto(string $name): bool {
		if (!preg_match('#^[A-Z][a-zA-Z/]+$#', $name)) {
			return false;
		}

		$pieces = explode('/', $name);
		foreach ($pieces as $piece) {
			$expected = Inflector::camelize(Inflector::underscore($piece));
			if ($piece !== $expected) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function isValidArray(string $type): bool {
		if ($type === 'array') {
			return true;
		}

		if (substr($type, -2) !== '[]') {
			return false;
		}

		$type = substr($type, 0, -2);
		if (substr($type, 0, 1) === '?') {
			$type = substr($type, 1);
		}

		return $this->isValidSimpleType($type) || $this->isValidDto($type) || $this->isValidInterfaceOrClass($type);
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function isValidCollection(string $type): bool {
		if ($type === 'array') {
			return true;
		}

		if (substr($type, -2) !== '[]') {
			return false;
		}

		$type = substr($type, 0, -2);

		return $this->isValidSimpleType($type) || $this->isValidDto($type) || $this->isValidInterfaceOrClass($type);
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function isValidInterfaceOrClass(string $type): bool {
		if (substr($type, 0, 1) !== '\\') {
			return false;
		}

		return interface_exists($type) || class_exists($type);
	}

	/**
	 * @param string $type
	 * @param array $additional
	 * @return bool
	 */
	protected function isValidSimpleType(string $type, array $additional = []): bool {
		$whitelist = array_merge($this->simpleTypeWhitelist, $additional);
		$types = explode('|', $type);

		// Non-union simple types with brackets are arrays
		if (count($types) === 1 && substr($types[0], -2) === '[]') {
			return false;
		}

		$types = array_map(function($value) {
			return substr($value, -2) !== '[]' ? $value : substr($value, 0, -2);
		}, $types);

		foreach ($types as $t) {
			if (!in_array($t, $whitelist, true)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array|null $existing
	 * @param array|null $new
	 * @throws \RuntimeException
	 * @return void
	 */
	protected function validateMerge(?array $existing, ?array $new): void {
		if (!$existing || !$new) {
			return;
		}

		foreach ($existing as $field => $info) {
			if (!isset($new[$field])) {
				continue;
			}
			if (!isset($info['type'])) {
				continue;
			}
			if (!isset($new[$field]['type'])) {
				continue;
			}

			if ($info['type'] !== $new[$field]['type']) {
				throw new RuntimeException('Invalid type mismatch for ' . $existing['name']);
			}
		}
	}

	/**
	 * @param string $configPath
	 * @return string[]
	 */
	protected function _getFiles(string $configPath): array {
		$extension = $this->engine->extension();

		$files = $this->_finder()->collect($configPath, $extension);

		$this->engine->validate($files);

		return $files;
	}

	/**
	 * @param string|null $namespace
	 * @param string|null $plugin
	 * @return string
	 */
	protected function _getNamespace(?string $namespace, ?string $plugin): string {
		if ($namespace) {
			return $namespace;
		}
		if ($plugin) {
			return $plugin;
		}

		return 'App';
	}

	/**
	 * @param array $types
	 *
	 * @return array
	 */
	protected function simpleTypeWhitelist(array $types): array {
		$types[] = 'iterable';
		$types[] = 'object';

		return $types;
	}

	/**
	 * @param string $type
	 * @return string|null
	 */
	protected function typehint(string $type): ?string {
		// Unset the typehint for simple type unions
		if ($this->isValidSimpleType($type)) {
			$types = explode('|', $type);
			if (count($types) > 1) {
				return null;
			}
		}
		if (in_array($type, $this->simpleTypeAdditionsForDocBlock, true)) {
			return null;
		}
		if (!$this->_config['scalarTypeHints'] && in_array($type, $this->simpleTypeWhitelist, true)) {
			return null;
		}

		return $type;
	}

	/**
	 * @param string $type
	 *
	 * @return string|null
	 */
	protected function singularType(string $type): ?string {
		if (substr($type, -2) !== '[]') {
			return null;
		}

		$type = substr($type, 0, -2);
		if (substr($type, 0, 1) === '?') {
			$type = substr($type, 1);
		}

		if (!$this->isValidSimpleType($type) && !$this->isValidDto($type) && !$this->isValidInterfaceOrClass($type)) {
			return null;
		}

		return $type;
	}

	/**
	 * @param array $fields
	 * @return array
	 */
	protected function metaData(array $fields): array {
		$meta = [];

		if ($this->_config['debug']) {
			return $fields;
		}

		$neededFields = array_combine($this->metaDataKeys, $this->metaDataKeys) ?: [];

		foreach ($fields as $name => $field) {
			$meta[$name] = array_intersect_key($field, $neededFields);
		}

		return $meta;
	}

	/**
	 * @return \CakeDto\Generator\Finder
	 */
	protected function _finder(): Finder {
		$finderClass = $this->_config['finder'];

		return new $finderClass();
	}

	/**
	 * @param string $singularType
	 * @param string $namespace
	 * @return string
	 */
	protected function dtoTypeToClass(string $singularType, string $namespace): string {
		return '\\' . $namespace . '\\Dto\\' . str_replace('/', '\\', $singularType) . 'Dto';
	}

}
