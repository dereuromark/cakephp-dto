<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Owner DTO
 *
 * @property string|null $name
 * @property int|null $birthYear
 */
class OwnerDto extends \CakeDto\Dto\AbstractDto {

	public const FIELD_NAME = 'name';
	public const FIELD_BIRTH_YEAR = 'birthYear';

	/**
	 * @var string|null
	 */
	protected $name;

	/**
	 * @var int|null
	 */
	protected $birthYear;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
		'name' => [
			'name' => 'name',
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
		'birthYear' => [
			'name' => 'birthYear',
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
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
		'underscored' => [
			'name' => 'name',
			'birth_year' => 'birthYear',
		],
		'dashed' => [
			'name' => 'name',
			'birth-year' => 'birthYear',
		],
	];

	/**
	 * @param string|null $name
	 *
	 * @return $this
	 */
	public function setName(?string $name) {
		$this->name = $name;
		$this->_touchedFields[self::FIELD_NAME] = true;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getNameOrFail(): string {
		if (!isset($this->name)) {
			throw new \RuntimeException('Value not set for field `name` (expected to be not null)');
		}

		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function hasName(): bool {
		return $this->name !== null;
	}

	/**
	 * @param int|null $birthYear
	 *
	 * @return $this
	 */
	public function setBirthYear(?int $birthYear) {
		$this->birthYear = $birthYear;
		$this->_touchedFields[self::FIELD_BIRTH_YEAR] = true;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getBirthYear(): ?int {
		return $this->birthYear;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return int
	 */
	public function getBirthYearOrFail(): int {
		if (!isset($this->birthYear)) {
			throw new \RuntimeException('Value not set for field `birthYear` (expected to be not null)');
		}

		return $this->birthYear;
	}

	/**
	 * @return bool
	 */
	public function hasBirthYear(): bool {
		return $this->birthYear !== null;
	}

}
