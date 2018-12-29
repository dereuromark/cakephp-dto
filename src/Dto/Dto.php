<?php

namespace CakeDto\Dto;

use ArrayAccess;
use CakeDto\View\Json;
use Cake\Collection\Collection;
use Countable;
use InvalidArgumentException;
use RuntimeException;
use Serializable;

abstract class Dto implements Serializable, ArrayAccess {

	/**
	 * Convenience wrapper for easier chaining.
	 *
	 * $myDto = (new MyDto($data))->...()
	 * can be
	 * $myDto = MyDto::create($data)->...()
	 *
	 * @param array|null $data
	 * @param bool $ignoreMissing
	 * @param string $type
	 * @return static
	 */
	public static function create(array $data = null, $ignoreMissing = false, $type = self::TYPE_DEFAULT) {
		return new static($data, $ignoreMissing, $type);
	}

	/**
	 * Default camelCased type.
	 *
	 * E.g. `myFieldName`
	 */
	const TYPE_DEFAULT = 'default';

	/**
	 * For DB and form input/output.
	 *
	 * E.g. `my_field_name`
	 */
	const TYPE_UNDERSCORED = 'underscored';

	/**
	 * For query string usage.
	 *
	 * E.g. `my-field-name`
	 */
	const TYPE_DASHED = 'dashed';

	/**
	 * For templating rendering.
	 *
	 * @var array
	 */
	protected $_metadata = [];

	/**
	 * For usage of inflections.
	 *
	 * @var array
	 */
	protected $_keyMap = [];

	/**
	 * Holds touched fields.
	 *
	 * @var array
	 */
	protected $_touchedFields = [];

	/**
	 * @param array|null $data
	 * @param bool $ignoreMissing
	 * @param string $type
	 */
	public function __construct(array $data = null, $ignoreMissing = false, $type = self::TYPE_DEFAULT) {
		if ($data) {
			$this->setFromArray($data, $ignoreMissing, $type);
		}

		$this->setDefaults();
		$this->validate();
	}

	/**
	 * @param string|null $type
	 * @param array|null $fields
	 * @param bool $touched
	 * @return array
	 */
	public function toArray($type = self::TYPE_DEFAULT, array $fields = null, $touched = false) {
		if ($fields === null) {
			$fields = $this->fields();
		}

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
				} elseif ($value instanceof Countable && $value->count()) {
					$values = $this->transformCollectiontoArray($value, $values, $key, $touched ? 'touchedToArray' : 'toArray', $type);
				} elseif ($this->_metadata[$field]['serializable']) {
					/** @var \CakeDto\Dto\FromArrayToArrayInterface $value */
					$values[$key] = $value->toArray();
				} elseif ($this->_metadata[$field]['toArray']) {
					// This will not be transformable in the other direction
					/** @var \CakeDto\Dto\FromArrayToArrayInterface $value */
					$values[$key] = $value->toArray();
				} else {
					$values[$key] = $value;
				}
				continue;
			}

			if ($value && $this->_metadata[$field]['collectionType'] === 'array') {
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
	protected function transformCollectiontoArray($value, $values, $arrayKey, $childConvertMethodName, $type) {
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
	protected function transformArrayCollectionToArray(array $array, $childConvertMethodName, $type) {
		foreach ($array as $elementKey => $arrayElement) {
			if (is_array($arrayElement) || is_scalar($arrayElement)) {
				continue;
			}

			$array[$elementKey] = $arrayElement->$childConvertMethodName($type);
		}

		return $array;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function touchedToArray($type = self::TYPE_DEFAULT) {
		return $this->toArray($type, $this->touchedFields(), true);
	}

	/**
	 * @param array $data
	 * @param bool $ignoreMissing
	 * @param string $type
	 * @return $this
	 * @throws \RuntimeException
	 */
	protected function setFromArray(array $data, $ignoreMissing, $type = self::TYPE_DEFAULT) {
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
				if ($collectionType === '\Cake\\Collection\\Collection') {
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

			} elseif ($this->_metadata[$field]['serializable']) {
				$value = $this->createObject($field, $value);
			}

			$this->$field = $value;
			$this->_touchedFields[$field] = true;
		}

		return $this;
	}

	/**
	 * @param string $elementType
	 * @param array|\ArrayObject $arrayObject
	 * @param bool $ignoreMissing
	 * @param string $type
	 *
	 * @return \Cake\Collection\Collection
	 */
	protected function createCakeCollection($elementType, $arrayObject, $ignoreMissing, $type = self::TYPE_DEFAULT) {
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
	 * @param mixed $value
	 * @param bool $ignoreMissing
	 * @param string $type
	 *
	 * @return \CakeDto\Dto\Dto
	 */
	protected function createDto($field, $value, $ignoreMissing, $type) {
		/** @var \CakeDto\Dto\AbstractDto $dto */
		$className = $this->_metadata[$field]['type'];

		if (is_array($value)) {
			$value = new $className($value, $ignoreMissing, $type);
		}

		return $value;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return \CakeDto\Dto\Dto
	 */
	protected function createObject($field, $value) {
		/** @var \CakeDto\Dto\FromArrayToArrayInterface $className */
		$className = $this->_metadata[$field]['type'];

		if (is_array($value)) {
			$value = $className::createFromArray($value);
		}

		return $value;
	}

	/**
	 * @param string $collectionType
	 * @param string $elementType
	 * @param array|\ArrayObject $arrayObject
	 * @param bool $ignoreMissing
	 * @param string $type
	 *
	 * @return \ArrayObject
	 */
	protected function createCollection($collectionType, $elementType, $arrayObject, $ignoreMissing, $type = self::TYPE_DEFAULT) {
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
	 * @param array|\ArrayObject $arrayObject
	 * @param bool $ignoreMissing
	 * @param string $type
	 * @param string|bool $key
	 *
	 * @return array
	 */
	protected function createArrayCollection($elementType, $arrayObject, $ignoreMissing, $type = self::TYPE_DEFAULT, $key = false) {
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
	protected function addValueToArrayCollection(array $collection, $element, $arrayElement, $index, $key) {
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
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	protected function hasField($field, $ignoreMissing, $type) {
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
			sprintf('Missing field `%s` in `%s` for type `%s`', $field, get_class($this), $type)
		);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->serialize();
	}

	/**
	 * Returns an array that can be used to describe the internal state of this
	 * object.
	 *
	 * @return array
	 */
	public function __debugInfo() {
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
	public function serialize() {
		$jsonUtil = new Json();

		return $jsonUtil->encode($this->touchedToArray());
	}

	/**
	 * @return string[]
	 */
	public function fields() {
		return array_keys($this->_metadata);
	}

	/**
	 * @return string[]
	 */
	public function touchedFields() {
		return array_keys($this->_touchedFields);
	}

	/**
	 * Lookup for dashed or underscored inflection of fields.
	 *
	 * @param string $name
	 * @param string $type Either dashed or underscored
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	protected function field($name, $type) {
		if (!isset($this->_keyMap[$type][$name])) {
			throw new InvalidArgumentException(sprintf('Invalid field lookup for type `%s`: `%s` does not exist.', $type, $name));
		}

		return $this->_keyMap[$type][$name];
	}

	/**
	 * @param string $key
	 * @param string $type
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	protected function key($key, $type) {
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
	protected function validate() {
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
	 * Whether a offset exists
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return isset($this->_metadata[$offset]);
	}

	/**
	 * Offset to retrieve
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 * @param string $offset
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset) {
		return $this->$offset;
	}

	/**
	 * Offset to set
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 * @param string $offset
	 * @param mixed $value
	 * @return void
	 * @throws \RuntimeException
	 */
	public function offsetSet($offset, $value) {
		throw new RuntimeException('DTO object as an array can only be read');
	}

	/**
	 * Offset to unset
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param string $offset
	 * @return void
	 * @throws \RuntimeException
	 */
	public function offsetUnset($offset) {
		throw new RuntimeException('DTO object as an array can only be read');
	}

}
