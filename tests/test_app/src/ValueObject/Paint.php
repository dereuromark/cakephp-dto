<?php

namespace TestApp\ValueObject;

use CakeDto\Dto\FromArrayToArrayInterface;

/**
 * Subclassing a Value Object is rare, so we cut out this scenario
 * immediately.
 * This class represent a category of paint, like 'blue' or 'red',
 * not a quantity.
 */
final class Paint implements FromArrayToArrayInterface {

	/**
	 * @var int
	 */
	protected $red;

	/**
	 * @var int
	 */
	protected $green;

	/**
	 * @var int
	 */
	protected $blue;

	/**
	 * This is paint for screens... No CMYK.
	 *
	 * @param int $red RGB value 0-255
	 * @param int $green RGB value 0-255
	 * @param int $blue RGB value 0-255
	 */
	public function __construct($red, $green, $blue) {
		$this->red = $red;
		$this->green = $green;
		$this->blue = $blue;
	}

	/**
	 * Getters expose the field of the Value Object we want to leave
	 * accessible (often all).
	 * There are no setters: once built, the Value Object is immutable.
	 *
	 * @return int
	 */
	public function getRed() {
		return $this->red;
	}

	/**
	 * @return int
	 */
	public function getGreen() {
		return $this->green;
	}

	/**
	 * @return int
	 */
	public function getBlue() {
		return $this->blue;
	}

	/**
	 * @param \TestApp\ValueObject\Paint $object
	 * @return bool True if the two Paints are equal
	 */
	public function equals(Paint $object) {
		return
			$this->red === $object->red
			&& $this->green === $object->green
			&& $this->blue === $object->blue;
	}

	/**
	 * Every kind of algorithm, just to expanding this example.
	 * Since the objects are immutable, the resulting Paint is a brand
	 * new object, which is returned.
	 *
	 * @param \TestApp\ValueObject\Paint $another
	 * @return \TestApp\ValueObject\Paint
	 */
	public function mix(Paint $another) {
		return new self($this->integerAverage($this->red, $another->red),
			$this->integerAverage($this->green, $another->green),
			$this->integerAverage($this->blue, $another->blue));
	}

	/**
	 * @param int $a
	 * @param int $b
	 *
	 * @return int
	 */
	protected function integerAverage($a, $b) {
		return (int)(($a + $b) / 2);
	}

	/**
	 * @param array $array
	 * @return static
	 */
	public static function createFromArray(array $array) {
		return new static($array['red'], $array['green'], $array['blue']);
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return [
			'red' => $this->red,
			'green' => $this->green,
			'blue' => $this->blue,
		];
	}

	/**
	 * @return string
	 */
	public function serialize() {
		return json_encode($this->toArray());
	}

	/**
	 * Returns a native string version (R, G, B).
	 *
	 * @return string
	 */
	public function __toString() {
		return sprintf('%s, %s, %s', $this->red, $this->green, $this->blue);
	}

	/**
	 * @param string $serialized
	 * @return static
	 */
	public function unserialize($serialized) {
		$array = json_decode($serialized, true);

		return static::createFromArray($array);
	}

}
