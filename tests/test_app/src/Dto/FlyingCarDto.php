<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * FlyingCar DTO
 */
class FlyingCarDto extends CarDto {

	const FIELD_MAX_ALTITUDE = 'maxAltitude';
	const FIELD_MAX_SPEED = 'maxSpeed';
	const FIELD_COMPLEX_ATTRIBUTES = 'complexAttributes';

	/**
	 * @var int|null
	 */
	protected $maxAltitude;

	/**
	 * @var int
	 */
	protected $maxSpeed;

	/**
	 * @var array|null
	 */
	protected $complexAttributes;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
		'maxAltitude' => [
			'name' => 'maxAltitude',
			'type' => 'int',
			'defaultValue' => 0,
			'required' => true,
			'isDto' => false,
			'class' => null,
			'singularClass' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
		'maxSpeed' => [
			'name' => 'maxSpeed',
			'type' => 'int',
			'defaultValue' => 0,
			'required' => true,
			'isDto' => false,
			'class' => null,
			'singularClass' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
		'complexAttributes' => [
			'name' => 'complexAttributes',
			'type' => 'array',
			'required' => false,
			'defaultValue' => null,
			'isDto' => false,
			'class' => null,
			'singularClass' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
		'underscored' => [
			'max_altitude' => 'maxAltitude',
			'max_speed' => 'maxSpeed',
			'complex_attributes' => 'complexAttributes',
		],
		'dashed' => [
			'max-altitude' => 'maxAltitude',
			'max-speed' => 'maxSpeed',
			'complex-attributes' => 'complexAttributes',
		],
	];

	/**
	 * @param int|null $maxAltitude
	 *
	 * @return $this
	 */
	public function setMaxAltitude($maxAltitude) {
		$this->maxAltitude = $maxAltitude;
		$this->_touchedFields[self::FIELD_MAX_ALTITUDE] = true;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getMaxAltitude() {
		return $this->maxAltitude;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return int
	 */
	public function getMaxAltitudeOrFail() {
		if (!isset($this->maxAltitude)) {
			throw new \RuntimeException('Value not set for field `maxAltitude` (expected to be not null)');
		}

		return $this->maxAltitude;
	}

	/**
	 * @return bool
	 */
	public function hasMaxAltitude() {
		return $this->maxAltitude !== null;
	}

	/**
	 * @param int $maxSpeed
	 *
	 * @return $this
	 */
	public function setMaxSpeed($maxSpeed) {
		$this->maxSpeed = $maxSpeed;
		$this->_touchedFields[self::FIELD_MAX_SPEED] = true;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getMaxSpeed() {
		return $this->maxSpeed;
	}

	/**
	 * @return bool
	 */
	public function hasMaxSpeed() {
		return $this->maxSpeed !== null;
	}

	/**
	 * @param array|null $complexAttributes
	 *
	 * @return $this
	 */
	public function setComplexAttributes(array $complexAttributes = null) {
		$this->complexAttributes = $complexAttributes;
		$this->_touchedFields[self::FIELD_COMPLEX_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getComplexAttributes() {
		return $this->complexAttributes;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return array
	 */
	public function getComplexAttributesOrFail() {
		if (!isset($this->complexAttributes)) {
			throw new \RuntimeException('Value not set for field `complexAttributes` (expected to be not null)');
		}

		return $this->complexAttributes;
	}

	/**
	 * @return bool
	 */
	public function hasComplexAttributes() {
		return $this->complexAttributes !== null;
	}

}
