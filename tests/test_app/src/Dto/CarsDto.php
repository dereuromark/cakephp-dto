<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Cars DTO
 *
 * @property \TestApp\Dto\CarDto[]|\ArrayObject $cars
 */
class CarsDto extends \Dto\Dto\AbstractDto {

	const FIELD_CARS = 'cars';

	/**
	 * @var \TestApp\Dto\CarDto[]|\ArrayObject
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
			'type' => '\TestApp\Dto\CarDto[]|\ArrayObject',
			'associative' => true,
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => '\ArrayObject',
			'key' => null,
			'serializable' => false,
			'toArray' => false,
			'singularType' => '\TestApp\Dto\CarDto',
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
	 * @param \TestApp\Dto\CarDto[]|\ArrayObject $cars
	 *
	 * @return $this
	 */
	public function setCars(\ArrayObject $cars) {
		$this->cars = $cars;
		$this->_touchedFields[self::FIELD_CARS] = true;

		return $this;
	}

	/**
	 * @return \TestApp\Dto\CarDto[]|\ArrayObject
	 */
	public function getCars() {
		if ($this->cars === null) {
			return new \ArrayObject([]);
		}

		return $this->cars;
	}

	/**
	 * @param string|int $key
	 *
	 * @return \TestApp\Dto\CarDto
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
	 * @param string|int $key
	 * @return bool
	 */
	public function hasCar($key) {
		return isset($this->cars[$key]);
	}

	/**
	 * @param string|int $key
	 * @param \TestApp\Dto\CarDto $car
	 * @return $this
	 */
	public function addCar($key, \TestApp\Dto\CarDto $car) {
		if (!isset($this->cars)) {
			$this->cars = new \ArrayObject([]);
		}

		$this->cars[$key] = $car;
		$this->_touchedFields[self::FIELD_CARS] = true;

		return $this;
	}

}
