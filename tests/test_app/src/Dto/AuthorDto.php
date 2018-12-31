<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Author DTO
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 */
class AuthorDto extends \CakeDto\Dto\AbstractImmutableDto {

	const FIELD_ID = 'id';
	const FIELD_NAME = 'name';
	const FIELD_EMAIL = 'email';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string|null
	 */
	protected $email;

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
		'email' => [
			'name' => 'email',
			'type' => 'string',
			'required' => false,
			'defaultValue' => null,
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
			'email' => 'email',
		],
		'dashed' => [
			'id' => 'id',
			'name' => 'name',
			'email' => 'email',
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
	 * @param string|null $email
	 *
	 * @return static
	 */
	public function withEmail($email) {
		$new = clone $this;
		$new->email = $email;
		$new->_touchedFields[self::FIELD_EMAIL] = true;

		return $new;
	}

	/**
	 * @return string|null
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getEmailOrFail() {
		if (!isset($this->email)) {
			throw new \RuntimeException('Value not set for field `email` (expected to be not null)');
		}

		return $this->email;
	}

	/**
	 * @return bool
	 */
	public function hasEmail() {
		return $this->email !== null;
	}

}
