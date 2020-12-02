<?php

namespace CakeDto\Dto;

use RuntimeException;

abstract class AbstractImmutableDto extends Dto {

	/**
	 * @param string $field
	 * @param mixed $value
	 * @param string $type
	 * @return static
	 * @throws \RuntimeException
	 */
	public function with(string $field, $value, string $type = self::TYPE_DEFAULT) {
		if ($type !== static::TYPE_DEFAULT) {
			$field = $this->field($field, $type);
		}

		if (!isset($this->_metadata[$field])) {
			throw new RuntimeException('Field does not exist: ' . $field);
		}

		$method = 'with' . ucfirst($field);

		return $this->$method($value);
	}

}
