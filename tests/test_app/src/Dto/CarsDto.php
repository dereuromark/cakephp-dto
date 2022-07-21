<?php declare(strict_types=1);
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
class CarsDto extends \CakeDto\Dto\AbstractDto {

	public const FIELD_CARS = 'cars';

	/**
	 * @var \TestApp\Dto\CarDto[]|\ArrayObject
	 */
	protected $cars;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
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
			'serialize' => null,
			'factory' => null,
			'singularType' => '\TestApp\Dto\CarDto',
			'singularNullable' => false,
			'singularTypeHint' => '\TestApp\Dto\CarDto',
		],
	];

	/**
	* @var array<string, array<string, string>>
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
	public function getCars(): \ArrayObject {
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
	public function getCar($key): \TestApp\Dto\CarDto {
		if (!isset($this->cars[$key])) {
			throw new \RuntimeException(sprintf('Value not set for field `cars` and key `%s` (expected to be not null)', $key));
		}

		return $this->cars[$key];
	}

	/**
	 * @return bool
	 */
	public function hasCars(): bool {
		if ($this->cars === null) {
			return false;
		}

		return $this->cars->count() > 0;
	}

	/**
	 * @param string|int $key
	 * @return bool
	 */
	public function hasCar($key): bool {
		return isset($this->cars[$key]);
	}

	/**
	 * @param string|int $key
	 * @param \TestApp\Dto\CarDto $car
	 * @return $this
	 */
	public function addCar($key, \TestApp\Dto\CarDto $car) {
		if ($this->cars === null) {
			$this->cars = new \ArrayObject([]);
		}

		$this->cars[$key] = $car;
		$this->_touchedFields[self::FIELD_CARS] = true;

		return $this;
	}

}
