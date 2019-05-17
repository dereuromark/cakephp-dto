<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Tag DTO
 *
 * @property int $id
 * @property string $name
 * @property int $weight
 */
class TagDto extends \Dto\Dto\AbstractImmutableDto {

	const FIELD_ID = 'id';
	const FIELD_NAME = 'name';
	const FIELD_WEIGHT = 'weight';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var int
	 */
	protected $weight;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
		'id' => [
			'name' => 'id',
			'type' => 'int',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
		'name' => [
			'name' => 'name',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
		],
		'weight' => [
			'name' => 'weight',
			'type' => 'int',
			'required' => true,
			'defaultValue' => 0,
			'dto' => null,
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
			'id' => 'id',
			'name' => 'name',
			'weight' => 'weight',
		],
		'dashed' => [
			'id' => 'id',
			'name' => 'name',
			'weight' => 'weight',
		],
	];

	/**
	 * @param int $id
	 *
	 * @return static
	 */
	public function withId($id) {
		$new = clone $this;
		$new->id = $id;
		$new->_touchedFields[self::FIELD_ID] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function hasId() {
		return $this->id !== null;
	}

	/**
	 * @param string $name
	 *
	 * @return static
	 */
	public function withName($name) {
		$new = clone $this;
		$new->name = $name;
		$new->_touchedFields[self::FIELD_NAME] = true;

		return $new;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function hasName() {
		return $this->name !== null;
	}

	/**
	 * @param int $weight
	 *
	 * @return static
	 */
	public function withWeight($weight) {
		$new = clone $this;
		$new->weight = $weight;
		$new->_touchedFields[self::FIELD_WEIGHT] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getWeight() {
		return $this->weight;
	}


}
