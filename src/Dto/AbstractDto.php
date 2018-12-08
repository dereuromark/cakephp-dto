<?php

namespace CakeDto\Dto;

use CakeDto\View\Json;

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

}
