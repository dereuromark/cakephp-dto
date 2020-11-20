<?php

namespace TestApp\ValueObject;

use Serializable;

/**
 * An example with a constructor with array
 */
class KeyValuePair implements Serializable {

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @phpstan-param array<string, mixed> $data
	 *
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->key = (string)$data['key'];
		$this->value = $data['value'];
	}

	/**
	 * @return string
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @phpstan-return array<string, mixed>
	 *
	 * @return array
	 */
	public function toArray(): array {
		return [
			'key' => $this->key,
			'value' => $this->value,
		];
	}

	/**
	 * @param string $string
	 *
	 * @return static
	 */
	public static function createFromString(string $string) {
		 $array = json_decode($string, true);

		 return static::createFromArray($array);
	}

	/**
	 * @phpstan-param array<string, mixed> $data
	 *
	 * @param array $array
	 *
	 * @return static
	 */
	public static function createFromArray(array $array) {
		return new static($array);
	}

	/**
	 * @return string
	 */
	public function serialize(): string {
		return (string)json_encode($this->toArray());
	}

	/**
	 * Returns a native string version.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->serialize();
	}

	/**
	 * @param string $serialized
	 *
	 * @return static
	 */
	public function unserialize($serialized) {
		return static::createFromString($serialized);
	}

	/**
	 * Returns array containing all the necessary state of the object.
	 *
	 * @return array
	 */
	public function __serialize(): array {
		return $this->toArray();
	}

	/**
	 * Restores the object state from the given data array.
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function __unserialize(array $data): void {
		$this->key = (string)$data['key'];
		$this->value = $data['value'];
	}

}
