<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Car DTO
 *
 * @property \TestApp\ValueObject\Paint|null $color
 * @property bool|null $isNew
 * @property float|null $value
 * @property int|null $distanceTravelled
 * @property string[]|null $attributes
 * @property \Cake\I18n\FrozenDate|null $manufactured
 * @property \TestApp\Dto\OwnerDto|null $owner
 */
class CarDto extends \Dto\Dto\AbstractDto {

	const FIELD_COLOR = 'color';
	const FIELD_IS_NEW = 'isNew';
	const FIELD_VALUE = 'value';
	const FIELD_DISTANCE_TRAVELLED = 'distanceTravelled';
	const FIELD_ATTRIBUTES = 'attributes';
	const FIELD_MANUFACTURED = 'manufactured';
	const FIELD_OWNER = 'owner';

	/**
	 * @var \TestApp\ValueObject\Paint|null
	 */
	protected $color;

	/**
	 * @var bool|null
	 */
	protected $isNew;

	/**
	 * @var float|null
	 */
	protected $value;

	/**
	 * @var int|null
	 */
	protected $distanceTravelled;

	/**
	 * @var string[]|null
	 */
	protected $attributes;

	/**
	 * @var \Cake\I18n\FrozenDate|null
	 */
	protected $manufactured;

	/**
	 * @var \TestApp\Dto\OwnerDto|null
	 */
	protected $owner;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
		'color' => [
			'name' => 'color',
			'type' => '\TestApp\ValueObject\Paint',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => true,
			'toArray' => false,
			'isClass' => true,
		],
		'isNew' => [
			'name' => 'isNew',
			'type' => 'bool',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
		'value' => [
			'name' => 'value',
			'type' => 'float',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
		'distanceTravelled' => [
			'name' => 'distanceTravelled',
			'type' => 'int',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
		'attributes' => [
			'name' => 'attributes',
			'type' => 'string[]',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
		'manufactured' => [
			'name' => 'manufactured',
			'type' => '\Cake\I18n\FrozenDate',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
			'isClass' => true,
		],
		'owner' => [
			'name' => 'owner',
			'type' => '\TestApp\Dto\OwnerDto',
			'required' => false,
			'defaultValue' => null,
			'dto' => 'Owner',
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
		'underscored' => [
			'color' => 'color',
			'is_new' => 'isNew',
			'value' => 'value',
			'distance_travelled' => 'distanceTravelled',
			'attributes' => 'attributes',
			'manufactured' => 'manufactured',
			'owner' => 'owner',
		],
		'dashed' => [
			'color' => 'color',
			'is-new' => 'isNew',
			'value' => 'value',
			'distance-travelled' => 'distanceTravelled',
			'attributes' => 'attributes',
			'manufactured' => 'manufactured',
			'owner' => 'owner',
		],
	];

	/**
	 * @param \TestApp\ValueObject\Paint|null $color
	 *
	 * @return $this
	 */
	public function setColor(\TestApp\ValueObject\Paint $color = null) {
		$this->color = $color;
		$this->_touchedFields[self::FIELD_COLOR] = true;

		return $this;
	}

	/**
	 * @return \TestApp\ValueObject\Paint|null
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\ValueObject\Paint
	 */
	public function getColorOrFail() {
		if (!isset($this->color)) {
			throw new \RuntimeException('Value not set for field `color` (expected to be not null)');
		}

		return $this->color;
	}

	/**
	 * @return bool
	 */
	public function hasColor() {
		return $this->color !== null;
	}

	/**
	 * @param bool|null $isNew
	 *
	 * @return $this
	 */
	public function setIsNew($isNew) {
		$this->isNew = $isNew;
		$this->_touchedFields[self::FIELD_IS_NEW] = true;

		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function getIsNew() {
		return $this->isNew;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return bool
	 */
	public function getIsNewOrFail() {
		if (!isset($this->isNew)) {
			throw new \RuntimeException('Value not set for field `isNew` (expected to be not null)');
		}

		return $this->isNew;
	}

	/**
	 * @return bool
	 */
	public function hasIsNew() {
		return $this->isNew !== null;
	}

	/**
	 * @param float|null $value
	 *
	 * @return $this
	 */
	public function setValue($value) {
		$this->value = $value;
		$this->_touchedFields[self::FIELD_VALUE] = true;

		return $this;
	}

	/**
	 * @return float|null
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return float
	 */
	public function getValueOrFail() {
		if (!isset($this->value)) {
			throw new \RuntimeException('Value not set for field `value` (expected to be not null)');
		}

		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function hasValue() {
		return $this->value !== null;
	}

	/**
	 * @param int|null $distanceTravelled
	 *
	 * @return $this
	 */
	public function setDistanceTravelled($distanceTravelled) {
		$this->distanceTravelled = $distanceTravelled;
		$this->_touchedFields[self::FIELD_DISTANCE_TRAVELLED] = true;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getDistanceTravelled() {
		return $this->distanceTravelled;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return int
	 */
	public function getDistanceTravelledOrFail() {
		if (!isset($this->distanceTravelled)) {
			throw new \RuntimeException('Value not set for field `distanceTravelled` (expected to be not null)');
		}

		return $this->distanceTravelled;
	}

	/**
	 * @return bool
	 */
	public function hasDistanceTravelled() {
		return $this->distanceTravelled !== null;
	}

	/**
	 * @param string[]|null $attributes
	 *
	 * @return $this
	 */
	public function setAttributes(array $attributes = null) {
		$this->attributes = $attributes;
		$this->_touchedFields[self::FIELD_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @return string[]|null
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string[]
	 */
	public function getAttributesOrFail() {
		if (!isset($this->attributes)) {
			throw new \RuntimeException('Value not set for field `attributes` (expected to be not null)');
		}

		return $this->attributes;
	}

	/**
	 * @return bool
	 */
	public function hasAttributes() {
		return $this->attributes !== null;
	}

	/**
	 * @param \Cake\I18n\FrozenDate|null $manufactured
	 *
	 * @return $this
	 */
	public function setManufactured(\Cake\I18n\FrozenDate $manufactured = null) {
		$this->manufactured = $manufactured;
		$this->_touchedFields[self::FIELD_MANUFACTURED] = true;

		return $this;
	}

	/**
	 * @return \Cake\I18n\FrozenDate|null
	 */
	public function getManufactured() {
		return $this->manufactured;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \Cake\I18n\FrozenDate
	 */
	public function getManufacturedOrFail() {
		if (!isset($this->manufactured)) {
			throw new \RuntimeException('Value not set for field `manufactured` (expected to be not null)');
		}

		return $this->manufactured;
	}

	/**
	 * @return bool
	 */
	public function hasManufactured() {
		return $this->manufactured !== null;
	}

	/**
	 * @param \TestApp\Dto\OwnerDto|null $owner
	 *
	 * @return $this
	 */
	public function setOwner(\TestApp\Dto\OwnerDto $owner = null) {
		$this->owner = $owner;
		$this->_touchedFields[self::FIELD_OWNER] = true;

		return $this;
	}

	/**
	 * @return \TestApp\Dto\OwnerDto|null
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\Dto\OwnerDto
	 */
	public function getOwnerOrFail() {
		if (!isset($this->owner)) {
			throw new \RuntimeException('Value not set for field `owner` (expected to be not null)');
		}

		return $this->owner;
	}

	/**
	 * @return bool
	 */
	public function hasOwner() {
		return $this->owner !== null;
	}

}
