<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Cars DTO
 */
class CarsDto extends \CakeDto\Dto\AbstractDto {

	const FIELD_CARS = 'cars';

	/**
	 * @var CarDto[]|\ArrayObject
	 */
	protected $cars;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
		'cars' => [
			'name' => 'cars',
			'type' => 'CarDto[]|\ArrayObject',
			'associative' => true,
			'required' => false,
			'defaultValue' => null,
			'isDto' => false,
			'class' => null,
			'singularClass' => '\TestApp\Dto\CarDto',
			'collectionType' => '\ArrayObject',
			'serializable' => false,
			'toArray' => false,
		],
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
		'underscored' => [
			'cars' => 'cars',
		],
		'dashed' => [
			'cars' => 'cars',
		],
	];

	/**
	 * @param CarDto[]|\ArrayObject $cars
	 *
	 * @return $this
	 */
	public function setCars(\ArrayObject $cars) {
		$this->cars = $cars;
		$this->_touchedFields[self::FIELD_CARS] = true;

		return $this;
	}

	/**
	 * @return CarDto[]|\ArrayObject
	 */
	public function getCars() {
		if ($this->cars === null) {
			return new \ArrayObject([]);
		}

		return $this->cars;
	}

	/**
	 * @param string $key
	 *
	 * @return CarDto
	 *
	 * @throws \RuntimeException If value with this key is not set.
	 */
	public function getCar($key) {
		if (!isset($this->cars[$key])) {
			throw new \RuntimeException(sprintf('Value not set for field `cars` and key `%s` (expected to be not null)', $key));
		}

		return $this->cars[$key];
	}

	/**
	 * @return bool
	 */
	public function hasCars() {
		if ($this->cars === null) {
			return false;
		}

		return $this->cars->count() > 0;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasCar($key) {
		return isset($this->cars[$key]);
	}

	/**
	 * @param string $key
	 * @param CarDto $car
	 * @return $this
	 */
	public function addCar($key, CarDto $car) {
		if (!isset($this->cars)) {
			$this->cars = new \ArrayObject([]);
		}

		$this->cars[$key] = $car;
		$this->_touchedFields[self::FIELD_CARS] = true;

		return $this;
	}

}
