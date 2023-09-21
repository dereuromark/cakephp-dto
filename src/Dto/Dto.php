<?php

namespace CakeDto\Dto;

use ArrayAccess;
use ArrayObject;
use Cake\Collection\Collection;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use CakeDto\View\Json;
use Countable;
use InvalidArgumentException;
use RuntimeException;
use Serializable;

abstract class Dto implements Serializable {

	/**
	 * @param array $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null) {
		return new static($data, $ignoreMissing, $type);
	}

	/**
	 * @param string $data
	 * @param bool $ignoreMissing
	 * @return static
	 */
	public static function fromUnserialized(string $data, bool $ignoreMissing = false) {
		$jsonUtil = new Json();

		return new static($jsonUtil->decode($data, true), $ignoreMissing);
	}

	/**
	 * Constructs the object
	 *
	 * @link https://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized
	 * @param bool $ignoreMissing
	 * @return static
	 */
	public function unserialize($serialized, $ignoreMissing = false) {
		$jsonUtil = new Json();

		$new = clone($this);
		$new->setFromArray($jsonUtil->decode($serialized, true) ?: [], $ignoreMissing, static::TYPE_DEFAULT)->setDefaults()->validate();

		return $new;
	}

	/**
	 * Convenience wrapper for easier chaining.
	 *
	 * $myDto = (new MyDto($data))->...()
	 * can be
	 * $myDto = MyDto::create($data)->...()
	 *
	 * @param array|null $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 * @return static
	 */
	public static function create(?array $data = null, bool $ignoreMissing = false, ?string $type = null) {
		return new static($data, $ignoreMissing, $type);
	}

	/**
	 * Default type, defaults to camelBacked
	 *
	 * E.g. `myFieldName`
	 *
	 * @var string
	 */
	public const TYPE_DEFAULT = 'default';

	/**
	 * For camelBacked input/output.
	 *
	 * E.g. `myFieldName`
	 *
	 * @var string
	 */
	public const TYPE_CAMEL = 'camel';

	/**
	 * For DB and form input/output.
	 *
	 * E.g. `my_field_name`
	 *
	 * @var string
	 */
	public const TYPE_UNDERSCORED = 'underscored';

	/**
	 * For query string usage.
	 *
	 * E.g. `my-field-name`
	 *
	 * @var string
	 */
	public const TYPE_DASHED = 'dashed';

	/**
	 * For templating rendering.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [];

	/**
	 * For usage of inflections.
	 *
	 * @var array
	 */
	protected array $_keyMap = [];

	/**
	 * Holds touched fields.
	 *
	 * @var array
	 */
	protected array $_touchedFields = [];

	/**
	 * @param array|null $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 */
	public function __construct(?array $data = null, bool $ignoreMissing = false, ?string $type = null) {
		if ($data) {
			$this->setFromArray($data, $ignoreMissing, $type);
		}

		$this->setDefaults();
		$this->validate();
	}

	/**
	 * @param array<string> $path Path as array of strings.
	 * @param mixed|null $default The return value when the path does not exist.
	 * @return mixed|null The value fetched from the DTO, or null.
	 */
	public function read(array $path, $default = null) {
		$data = null;
		foreach ($path as $key) {
			if ($data === null && !$this->has($key)) {
				return $default;
			}
			if ($data === null) {
				$data = $this->get($key);

				continue;
			}
			if ($data instanceof self || $data instanceof FromArrayToArrayInterface) {
				$data = $data->toArray();
			}
			if ((is_array($data) || $data instanceof ArrayAccess) && isset($data[$key])) {
				$data = $data[$key];
			} else {
				return $default;
			}
		}

		return $data;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 * @return array
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		if ($fields === null) {
			$fields = $this->fields();
		}
		$type = $this->keyType($type);

		$values = [];
		foreach ($fields as $field) {
			$value = $this->$field;

			$key = $field;
			if ($type !== static::TYPE_DEFAULT) {
				$key = $this->key($key, $type);
			}

			if (is_object($value)) {
				if ($value instanceof self) {
					$values[$key] = $touched ? $value->touchedToArray($type) : $value->toArray($type);
				} elseif ($value instanceof Countable) {
					$values = $this->transformCollectionToArray($value, $values, $key, $touched ? 'touchedToArray' : 'toArray', $type);
				} elseif ($this->_metadata[$field]['serialize']) {
					$values[$key] = $this->transformSerialized($value, $this->_metadata[$field]['serialize']);
				} else {
					$values[$key] = $value;
				}

				continue;
			}

			if ($this->_metadata[$field]['collectionType'] === 'array') {
				if (!$value) {
					$value = [];
				}
				$value = $this->transformArrayCollectionToArray($value, $touched ? 'touchedToArray' : 'toArray', $type);
			}

			$values[$key] = $value;
		}

		return $values;
	}

	/**
	 * @param mixed $value
	 * @param array $values
	 * @param string $arrayKey
	 * @param string $childConvertMethodName
	 * @param string $type
	 *
	 * @return array
	 */
	protected function transformCollectionToArray($value, array $values, string $arrayKey, string $childConvertMethodName, string $type): array {
		if ($value->count() === 0) {
			$values[$arrayKey] = [];

			return $values;
		}

		foreach ($value as $elementKey => $arrayElement) {
			if (is_array($arrayElement) || is_scalar($arrayElement)) {
				$values[$arrayKey][$elementKey] = $arrayElement;

				continue;
			}

			$values[$arrayKey][$elementKey] = $arrayElement->$childConvertMethodName($type);
		}

		return $values;
	}

	/**
	 * @param array $array
	 * @param string $childConvertMethodName
	 * @param string $type
	 *
	 * @return array
	 */
	protected function transformArrayCollectionToArray(array $array, string $childConvertMethodName, string $type): array {
		foreach ($array as $elementKey => $arrayElement) {
			if (is_array($arrayElement) || is_scalar($arrayElement)) {
				continue;
			}

			$array[$elementKey] = $arrayElement->$childConvertMethodName($type);
		}

		return $array;
	}

	/**
	 * @param string|null $type
	 * @return array
	 */
	public function touchedToArray(?string $type = null): array {
		return $this->toArray($type, $this->touchedFields(), true);
	}

	/**
	 * @param array $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 * @throws \RuntimeException
	 * @return $this
	 */
	protected function setFromArray(array $data, bool $ignoreMissing, ?string $type = null) {
		$immutable = $this instanceof AbstractImmutableDto;

		$type = $this->keyType($type);
		foreach ($data as $field => $value) {
			if (!$this->hasField($field, $ignoreMissing, $type)) {
				continue;
			}

			if ($type !== static::TYPE_DEFAULT) {
				$field = $this->field($field, $type);
			}

			if ($this->_metadata[$field]['dto']) {
				$value = $this->createDto($field, $value, $ignoreMissing, $type);
			} elseif ($this->_metadata[$field]['collectionType'] && $this->_metadata[$field]['collectionType'] !== 'array') {
				$collectionType = $this->_metadata[$field]['collectionType'];
				$elementType = $this->_metadata[$field]['singularType'];
				if (!$elementType) {
					throw new RuntimeException('Missing singularType for collection ' . $collectionType);
				}
				if ($collectionType === '\\Cake\\Collection\\Collection') {
					$value = $this->createCakeCollection($elementType, $value, $ignoreMissing, $type);
				} else {
					$value = $this->createCollection($collectionType, $elementType, $value, $ignoreMissing, $type);
				}
			} elseif ($this->_metadata[$field]['collectionType'] && $this->_metadata[$field]['collectionType'] === 'array') {
				$elementType = $this->_metadata[$field]['singularType'];
				$key = $this->_metadata[$field]['associative'];
				if ($this->_metadata[$field]['associative'] && $this->_metadata[$field]['key']) {
					$key = $this->_metadata[$field]['key'];
				}
				$value = $this->createArrayCollection($elementType, $value, $ignoreMissing, $type, $key);

			} elseif ($this->_metadata[$field]['serialize'] === 'FromArrayToArray') {
				$value = $this->createObject($field, $value);
			} elseif ($this->_metadata[$field]['serialize'] === 'array') {
				$value = $this->createObject($field, $value);
			} elseif ($this->_metadata[$field]['factory']) {
				$value = $this->createWithFactory($field, $value);
			} elseif (!empty($this->_metadata[$field]['isClass']) && !is_object($value)) {
				$value = $this->createWithConstructor($field, $value);
			}

			if (!$immutable) {
				$method = 'set' . ucfirst($field);
				$this->$method($value);
			} else {
				$this->assertType($field, $value);
				$this->$field = $value;
				$this->_touchedFields[$field] = true;
			}
		}

		return $this;
	}

	/**
	 * @param string $elementType
	 * @param \ArrayObject|array $arrayObject
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return \Cake\Collection\CollectionInterface
	 */
	protected function createCakeCollection(string $elementType, $arrayObject, bool $ignoreMissing, ?string $type = null): CollectionInterface {
		$collection = new Collection([]);
		foreach ($arrayObject as $arrayElement) {
			if (!is_array($arrayElement)) {
				$collection = $collection->appendItem(new $elementType());

				continue;
			}

			if (array_values($arrayElement) !== $arrayElement) {
				/** @var \CakeDto\Dto\Dto $dto */
				$dto = new $elementType($arrayElement, $ignoreMissing, $type);
				$collection = $collection->appendItem($dto);

				continue;
			}

			foreach ($arrayElement as $arrayElementItem) {
				/** @var \CakeDto\Dto\Dto $dto */
				$dto = new $elementType($arrayElementItem, $ignoreMissing, $type);
				$collection = $collection->appendItem($dto);
			}
		}

		return $collection;
	}

	/**
	 * @param string $field
	 * @param static|array $value
	 * @param bool $ignoreMissing
	 * @param string $type
	 *
	 * @return static
	 */
	protected function createDto(string $field, $value, bool $ignoreMissing, string $type) {
		$className = $this->_metadata[$field]['type'];

		if (is_array($value)) {
			/** @var static $value */
			$value = new $className($value, $ignoreMissing, $type);
		}

		return $value;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return object|string
	 */
	protected function createObject(string $field, $value) {
		/** @var \CakeDto\Dto\FromArrayToArrayInterface $className */
		$className = $this->_metadata[$field]['type'];

		if (is_array($value)) {
			$value = $className::createFromArray($value);
		}

		return $value;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function createWithFactory(string $field, $value) {
		$factory = $this->_metadata[$field]['factory'];
		$class = $this->_metadata[$field]['type'];
		if ($value instanceof $class) {
			return $value;
		}

		if (strpos($factory, '::') !== false) {
			[$class, $factory] = explode('::', $factory, 2);
		}

		return $class::$factory($value);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return object
	 */
	protected function createWithConstructor(string $field, $value) {
		$class = $this->_metadata[$field]['type'];

		return new $class($value);
	}

	/**
	 * @param string $collectionType
	 * @param string $elementType
	 * @param \ArrayObject|array $arrayObject
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return \ArrayObject
	 */
	protected function createCollection(string $collectionType, string $elementType, $arrayObject, bool $ignoreMissing, ?string $type = null): ArrayObject {
		/** @var \ArrayObject $collection */
		$collection = new $collectionType();
		foreach ($arrayObject as $arrayElement) {
			if (!is_array($arrayElement)) {
				$collection->append(new $elementType());

				continue;
			}

			if (array_values($arrayElement) !== $arrayElement) {
				/** @var \CakeDto\Dto\Dto $dto */
				$dto = new $elementType($arrayElement, $ignoreMissing, $type);
				$collection->append($dto);

				continue;
			}

			foreach ($arrayElement as $arrayElementItem) {
				/** @var \CakeDto\Dto\Dto $dto */
				$dto = new $elementType($arrayElementItem, $ignoreMissing, $type);
				$collection->append($dto);
			}
		}

		return $collection;
	}

	/**
	 * @param string $elementType
	 * @param \ArrayObject|array $arrayObject
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 * @param string|bool $key
	 *
	 * @return array
	 */
	protected function createArrayCollection(string $elementType, $arrayObject, bool $ignoreMissing, ?string $type = null, $key = false): array {
		$collection = [];
		foreach ($arrayObject as $index => $arrayElement) {
			if (!is_array($arrayElement)) {
				$collection = $this->addValueToArrayCollection($collection, new $elementType(), $arrayElement, $index, $key);

				continue;
			}

			if (array_values($arrayElement) !== $arrayElement) {
				/** @var \CakeDto\Dto\Dto $dto */
				$dto = new $elementType($arrayElement, $ignoreMissing, $type);
				$collection = $this->addValueToArrayCollection($collection, $dto, $arrayElement, $index, $key);

				continue;
			}

			foreach ($arrayElement as $arrayElementItem) {
				/** @var \CakeDto\Dto\Dto $dto */
				$dto = new $elementType($arrayElementItem, $ignoreMissing, $type);
				$collection = $this->addValueToArrayCollection($collection, $dto, $arrayElement, $index, $key);
			}
		}

		return $collection;
	}

	/**
	 * @param array $collection
	 * @param mixed $element
	 * @param mixed $arrayElement
	 * @param string|int $index
	 * @param string|bool $key
	 *
	 * @return array
	 */
	protected function addValueToArrayCollection(array $collection, $element, $arrayElement, $index, $key): array {
		if ($key === false) {
			$collection[] = $element;

			return $collection;
		}
		if ($key === true) {
			$collection[(string)$index] = $element;

			return $collection;
		}

		if (is_array($arrayElement) && isset($arrayElement[$key])) {
			$index = $arrayElement[$key];
		}

		$collection[(string)$index] = $element;

		return $collection;
	}

	/**
	 * @param string $field
	 * @param bool $ignoreMissing
	 * @param string $type
	 * @throws \InvalidArgumentException
	 * @return bool
	 */
	protected function hasField(string $field, bool $ignoreMissing, string $type): bool {
		if ($type === static::TYPE_DEFAULT && isset($this->_metadata[$field])) {
			return true;
		}
		if ($type !== static::TYPE_DEFAULT && isset($this->_keyMap[$type][$field])) {
			return true;
		}

		if ($ignoreMissing) {
			return false;
		}

		throw new InvalidArgumentException(
			sprintf('Missing field `%s` in `%s` for type `%s`', $field, static::class, $type),
		);
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->serialize();
	}

	/**
	 * Returns an array that can be used to describe the internal state of this
	 * object.
	 *
	 * @return array
	 */
	public function __debugInfo(): array {
		return [
			'data' => $this->toArray(),
			'touched' => $this->touchedFields(),
			'extends' => get_parent_class($this),
			'immutable' => $this instanceof AbstractImmutableDto,
		];
	}

	/**
	 * String representation of object
	 *
	 * @link https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string {
		$jsonUtil = new Json();

		return $jsonUtil->encode($this->touchedToArray());
	}

	/**
	 * @return array<string>
	 */
	public function fields(): array {
		return array_keys($this->_metadata);
	}

	/**
	 * @return array<string>
	 */
	public function touchedFields(): array {
		return array_keys($this->_touchedFields);
	}

	/**
	 * Lookup for dashed or underscored inflection of fields.
	 *
	 * @param string $name
	 * @param string $type Either dashed or underscored
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	public function field(string $name, string $type): string {
		if (!isset($this->_keyMap[$type][$name])) {
			throw new InvalidArgumentException(sprintf('Invalid field lookup for type `%s`: `%s` does not exist.', $type, $name));
		}

		return $this->_keyMap[$type][$name];
	}

	/**
	 * @param string $key
	 * @param string $type
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	protected function key(string $key, string $type): string {
		$map = array_flip($this->_keyMap[$type]);
		if (!isset($map[$key])) {
			throw new InvalidArgumentException(sprintf('Invalid field lookup for type `%s`: `%s` does not exist.', $type, $key));
		}

		return $map[$key];
	}

	/**
	 * @return $this
	 */
	protected function setDefaults() {
		foreach ($this->_metadata as $name => $field) {
			if ($field['defaultValue'] === null || $this->$name !== null) {
				continue;
			}

			$this->$name = $field['defaultValue'];
		}

		return $this;
	}

	/**
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	protected function validate(): void {
		$errors = [];
		foreach ($this->_metadata as $name => $field) {
			if ($field['required'] && $this->$name === null) {
				$errors[] = $name;
			}
		}
		if ($errors) {
			throw new InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
		}
	}

	/**
	 * Magic getter to access properties that have been set in this entity
	 *
	 * @param string $property Name of the property to access
	 * @return mixed
	 */
	public function &__get(string $property) {
		return $this->get($property);
	}

	/**
	 * Returns whether this entity contains a property named $property
	 * regardless of if it is empty.
	 *
	 * @see \Cake\ORM\Entity::has()
	 * @param string $property The property to check.
	 * @return bool
	 */
	public function __isset(string $property): bool {
		return $this->has($property);
	}

	/**
	 * @param string $field
	 * @param string|null $type
	 * @throws \RuntimeException
	 * @return bool
	 */
	public function has(string $field, ?string $type = null): bool {
		$type = $this->keyType($type);
		if ($type !== static::TYPE_DEFAULT) {
			$field = $this->field($field, $type);
		}

		if (!isset($this->_metadata[$field])) {
			throw new RuntimeException('Field does not exist: ' . $field);
		}

		$method = 'has' . ucfirst($field);

		return $this->$method();
	}

	/**
	 * @param string $field
	 * @param string|null $type
	 * @throws \RuntimeException
	 * @return mixed
	 */
	public function &get(string $field, ?string $type = null) {
		$type = $this->keyType($type);
		if ($type !== static::TYPE_DEFAULT) {
			$field = $this->field($field, $type);
		}

		if (!isset($this->_metadata[$field])) {
			throw new RuntimeException('Field does not exist: ' . $field);
		}

		$method = 'get' . ucfirst($field);

		$result = $this->$method();

		return $result;
	}

	/**
	 * Scalar and array type checks for mass assignment.
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function assertType(string $field, $value): void {
		// Missing fields will be checked afterwards
		if ($value === null) {
			return;
		}

		$expectedType = $this->_metadata[$field]['type'];
		$actualType = $this->type($value);
		$types = ['bool', 'int', 'string', 'double'];

		if ($expectedType === 'float' && in_array($actualType, ['float', 'int'], true)) {
			return;
		}

		if (in_array($expectedType, $types, true) || in_array($actualType, $types, true)) {
			if ($actualType === $expectedType) {
				return;
			}

			throw new InvalidArgumentException(sprintf('Type of field `%s` is `%s`, expected `%s`.', $field, $actualType, $expectedType));
		}
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function type($value): string {
		$type = gettype($value);
		if ($type === 'boolean') {
			$type = 'bool';
		} elseif ($type === 'double') {
			$type = 'float';
		} elseif ($type === 'integer') {
			$type = 'int';
		}

		return $type;
	}

	/**
	 * @param string|null $type
	 *
	 * @return string
	 */
	protected function keyType(?string $type): string {
		if ($type === null) {
			/** @var string|null $type */
			$type = Configure::read('CakeDto.keyType');
		}

		if ($type === null || $type === static::TYPE_CAMEL) {
			return static::TYPE_DEFAULT;
		}

		return $type;
	}

	/**
	 * @param object $value
	 * @param string $serialize
	 *
	 * @return array|string
	 */
	protected function transformSerialized(object $value, string $serialize) {
		if ($serialize === 'FromArrayToArray') {
			/** @var \CakeDto\Dto\FromArrayToArrayInterface $value */
			return $value->toArray();
		}
		if ($serialize === 'array') {
			// This will not be transformable in the other direction without FromArrayToArrayInterface or mapping

			/** @var \CakeDto\Dto\FromArrayToArrayInterface $value */
			return $value->toArray();
		}
		if ($serialize === 'string') {
			/** @var \Stringable $value */
			return (string)$value;
		}

		throw new InvalidArgumentException('Cannot determine serialize type from `' . $serialize . '`.');
	}

	/**
	 * @return array<string, mixed>
	 */
	public function __serialize(): array {
		return $this->touchedToArray();
	}

	/**
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function __unserialize(array $data): void {
		$this->setFromArray($data, true);
	}

}
