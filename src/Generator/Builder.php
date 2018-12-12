<?php

namespace CakeDto\Generator;

use CakeDto\Dto\FromArrayToArrayInterface;
use CakeDto\Engine\EngineInterface;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Utility\Inflector;
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
		'resource',
	];

	/**
	 * Needed for for Dto to work dynamically.
	 *
	 * @var array
	 */
	protected $metaDataKeys = [
		'name',
		'type',
		'class',
		'singularClass',
		'serializable',
		'required',
		'defaultValue',
		'isDto',
		'toArray',
		'collectionType',
		'associative',
	];

	/**
	 * @param \CakeDto\Engine\EngineInterface $engine
	 */
	public function __construct(EngineInterface $engine) {
		$this->engine = $engine;

		$this->simpleTypeWhitelist = $this->simpleTypeWhitelist($this->simpleTypeWhitelist);
		$config = [
			'scalarTypeHints' => Configure::read('CakeDto.scalarTypeHints', version_compare(PHP_VERSION, '7.1') >= 0),
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
	public function build($configPath, array $options = []) {
		$options += [
			'plugin' => null,
			'namespace' => null,
		];
		$namespace = $this->_getNamespace($options['namespace'], $options['plugin']);

		$files = $this->_getFiles($configPath);

		$config = [];
		foreach ($files as $file) {
			$content = file_get_contents($file);
			$config[$file] = $this->engine->parse($content);
		}

		$result = $this->_merge($config);

		return $this->_createDtos($result, $namespace);
	}

	/**
	 * @param array $config
	 * @param string $namespace
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function _createDtos(array $config, $namespace) {
		foreach ($config as $name => $dto) {
			$this->_validateDto($dto);
			$dto = $this->_complete($dto, $namespace);
			$dto = $this->_completeMeta($dto);

			$dto += [
				'immutable' => $this->_config['immutable'],
				'namespace' => $namespace . '\Dto',
				'className' => $name . 'Dto',
				'extends' => '\\CakeDto\\Dto\\AbstractDto',
			];

			if (!empty($dto['immutable'])) {
				$dto['extends'] = '\\CakeDto\\Dto\\AbstractImmutableDto';
			}

			$config[$name] = $dto;
		}

		foreach ($config as $name => $dto) {
			if (in_array($dto['extends'], ['\\CakeDto\\Dto\\AbstractDto', '\\CakeDto\\Dto\\AbstractImmutableDto'])) {
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

		return $config;
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	protected function isValidType($type) {
		if ($this->isValidSimpleType($type)) {
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
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	protected function _validateDto(array $dto) {
		if (empty($dto['name'])) {
			throw new InvalidArgumentException('DTO name missing, but required.');
		}
		$dtoName = $dto['name'];
		$expected = Inflector::camelize(Inflector::underscore($dtoName));
		if ($dtoName !== $expected) {
			throw new InvalidArgumentException(sprintf('Invalid DTO name `%s`, expected `%s`', $dtoName, $expected));
		}

		if (!empty($dto['extends']) && !preg_match('/^[A-Z][a-zA-Z]+$/', $dto['extends'])) {
			throw new InvalidArgumentException(sprintf('Invalid %s DTO attribute `extends`: `%s`. Only DTOs are allowed.', $dtoName, $dto['extends']));
		}

		$fields = $dto['fields'];

		foreach ($fields as $name => $array) {
			if (empty($array['name'])) {
				throw new InvalidArgumentException(sprintf('Field attribute `%s:name` in %s DTO missing, but required.', $name, $dtoName));
			}
			if (empty($array['type'])) {
				throw new InvalidArgumentException(sprintf('Field attribute `%s:type` in %s DTO missing, but required.', $name, $dtoName));
			}
			foreach ($array as $key => $value) {
				$expected = Inflector::variable(Inflector::underscore($key));
				if ($key !== $expected) {
					throw new InvalidArgumentException(sprintf('Invalid field attribute `%s:%s` in %s DTO, expected `%s`', $name, $key, $dtoName, $expected));
				}
			}

			if (!$this->isValidType($array['type'])) {
				throw new InvalidArgumentException(sprintf('Invalid field attribute `%s:type` in %s DTO: `%s`.', $name, $dtoName, $array['type']));
			}
		}

		if (!empty($fields['singular'])) {
			$expected = Inflector::variable(Inflector::underscore($fields['singular']));
			if ($fields['singular'] !== $expected) {
				throw new InvalidArgumentException(sprintf('Invalid DTO attribute `%s:singular`, expected `%s`', $dtoName, $expected));
			}
		}
	}

	/**
	 * @param array $configs
	 *
	 * @return array
	 */
	protected function _merge(array $configs) {
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
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function _complete(array $dto, $namespace) {
		$fields = $dto['fields'];
		foreach ($fields as $field => $data) {
			$data += [
				'required' => isset($data['defaultValue']),
				'defaultValue' => null,
				'nullable' => empty($data['required']),
				'isArray' => false,
				'isDto' => false,
				'class' => null,
				'singularClass' => null,
				'collection' => false,
				'collectionType' => null,
				'associative' => false,
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
			if ($this->isValidSimpleType($field['type'])) {
				continue;
			}
			if ($this->isValidDto($field['type'])) {
				$fields[$key]['isDto'] = true;
				$fields[$key]['class'] = '\\' . $namespace . '\\Dto\\' . $field['type'] . 'Dto';
				continue;
			}
			if ($this->isCollection($field)) {
				$fields[$key]['collection'] = true;
				$fields[$key]['collectionType'] = $this->collectionType($field);
				$fields[$key]['nullable'] = false;

				$fields[$key] = $this->_completeCollectionSingular($fields[$key], $dto['name'], $namespace);

				if (preg_match('#^([A-Z]\w+)\[\]$#', $field['type'], $matches)) {
					$fields[$key]['type'] = $matches[1] . 'Dto[]';
				}

				continue;
			}
			if ($this->isValidArray($field['type'])) {
				$fields[$key]['isArray'] = true;
				if (preg_match('#^([A-Z]\w+)\[\]$#', $field['type'], $matches)) {
					$fields[$key]['type'] = $matches[1] . 'Dto[]';
				}

				continue;
			}

			if ($this->isValidInterfaceOrClass($field['type'])) {
				$fields[$key]['class'] = $field['type'];

				$serializable = is_subclass_of($fields[$key]['class'], FromArrayToArrayInterface::class);
				$fields[$key]['serializable'] = $serializable;
				if (!$serializable) {
					$fields[$key]['toArray'] = method_exists($fields[$key]['class'], 'toArray');
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
	protected function isCollection(array $field) {
		if (!$field['collection'] && !$field['collectionType'] && !$field['associative']) {
			return false;
		}

		return true;
	}

	/**
	 * @param array $data
	 * @param string $dtoName
	 * @param string $namespace
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function _completeCollectionSingular(array $data, $dtoName, $namespace) {
		$fieldName = $data['name'];
		if (!$data['collection'] && empty($data['collectionType'])) {
			return $data;
		}

		$data['singularType'] = $this->singularType($data['type']);
		if ($this->isValidDto($data['singularType'])) {
			$data['singularType'] .= 'Dto';
			$data['singularClass'] = '\\' . $namespace . '\\Dto\\' . $data['singularType'];
		}

		if (!empty($data['singular'])) {
			return $data;
		}

		if (preg_match('#^([A-Z]\w+)\[\]$#', $data['type'], $matches)) {
			$singular = $matches[1];

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
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function _completeMeta(array $dto) {
		$fields = $dto['fields'];

		foreach ($fields as $key => $field) {
			$fields[$key]['typeHint'] = $this->typehint($field['type']);

			if ($field['isDto']) {
				$fields[$key]['typeHint'] = $field['type'] . 'Dto';
				$fields[$key]['type'] = $field['type'] . 'Dto';
			}

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
	protected function collectionType(array $field) {
		if ($field['collectionType']) {
			return $field['collectionType'];
		}
		if ($field['collection']) {
			return $this->_config['defaultCollectionType'];
		}

		return 'array';
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function isValidDto($type) {
		return (bool)preg_match('#^[A-Z]\w+$#', $type);
	}

	protected function isValidArray($type) {
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
	protected function isValidCollection($type) {
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
	protected function isValidInterfaceOrClass($type) {
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
	protected function isValidSimpleType($type, array $additional = []) {
		$whitelist = array_merge($this->simpleTypeWhitelist, $additional);
		if (in_array($type, $whitelist, true)) {
			return true;
		}

		return false;
	}

	/**
	 * @param array|null $existing
	 * @param array|null $new
	 * @return void
	 * @throws \RuntimeException
	 */
	protected function validateMerge($existing, $new) {
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
	protected function _getFiles($configPath) {
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
	protected function _getNamespace($namespace, $plugin) {
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
	protected function simpleTypeWhitelist(array $types) {
		if (version_compare(PHP_VERSION, '7.1') >= 0) {
			$types[] = 'iterable';
		}
		if (version_compare(PHP_VERSION, '7.2') >= 0) {
			$types[] = 'object';
		}

		return $types;
	}

	/**
	 * @param string $type
	 * @return string|null
	 */
	protected function typehint($type) {
		if (!$this->_config['scalarTypeHints'] && in_array($type, $this->simpleTypeWhitelist)) {
			return null;
		}

		return $type;
	}

	/**
	 * @param string $type
	 *
	 * @return string|null
	 */
	protected function singularType($type) {
		if (substr($type, -2) !== '[]') {
			return null;
		}

		$type = substr($type, 0, -2);
		if (!$this->isValidSimpleType($type) && !$this->isValidDto($type) && !$this->isValidInterfaceOrClass($type)) {
			return null;
		}

		return $type;
	}

	/**
	 * @param array $fields
	 * @return array
	 */
	protected function metaData($fields) {
		$meta = [];

		if ($this->_config['debug']) {
			return $fields;
		}

		$neededFields = array_combine($this->metaDataKeys, $this->metaDataKeys);

		foreach ($fields as $name => $field) {
			$meta[$name] = array_intersect_key($field, $neededFields);
		}

		return $meta;
	}

	/**
	 * @return \CakeDto\Generator\Finder
	 */
	protected function _finder() {
		$finderClass = $this->_config['finder'];

		return new $finderClass();
	}

}
