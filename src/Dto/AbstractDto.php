<?php

namespace Dto\Dto;

use Dto\View\Json;
use RuntimeException;

abstract class AbstractDto extends Dto {

	/**
	 * @param array $data
	 * @param bool $ignoreMissing
	 * @param string|bool $type
	 * @return $this
	 */
	public function fromArray(array $data, $ignoreMissing = false, $type = self::TYPE_DEFAULT) {
		return $this->setFromArray($data, $ignoreMissing, $type);
	}

	/**
	 * Constructs the object
	 *
	 * @link https://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized
	 * @param bool $ignoreMissing
	 * @return $this
	 */
	public function unserialize($serialized, $ignoreMissing = false) {
		$jsonUtil = new Json();
		$this->fromArray($jsonUtil->decode($serialized, true), $ignoreMissing, static::TYPE_DEFAULT);

		return $this;
	}

	/**
	 * Magic setter to add or edit a property in this entity
	 *
	 * @param string $property The name of the property to set
	 * @param mixed $value The value to set to the property
	 * @return void
	 */
	public function __set($property, $value) {
		$this->set($property, $value);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @param string $type
	 * @return $this
	 * @throws \RuntimeException
	 */
	public function set($field, $value, $type = self::TYPE_DEFAULT) {
		if ($type !== static::TYPE_DEFAULT) {
			$field = $this->field($field, $type);
		}

		if (!isset($this->_metadata[$field])) {
			throw new RuntimeException('Field does not exist: ' . $field);
		}

		$method = 'set' . ucfirst($field);
		$this->$method($value);

		return $this;
	}

}
