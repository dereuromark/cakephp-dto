<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * CustomerAccount DTO
 *
 * @property string $customerName
 * @property int|null $birthYear
 */
class CustomerAccountDto extends \CakeDto\Dto\AbstractDto {

	public const FIELD_CUSTOMER_NAME = 'customerName';
	public const FIELD_BIRTH_YEAR = 'birthYear';

	/**
	 * @var string
	 */
	protected $customerName;

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
		'customerName' => [
			'name' => 'customerName',
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
			'customer_name' => 'customerName',
			'birth_year' => 'birthYear',
		],
		'dashed' => [
			'customer-name' => 'customerName',
			'birth-year' => 'birthYear',
		],
	];

	/**
	 * @param string $customerName
	 *
	 * @return $this
	 */
	public function setCustomerName(string $customerName) {
		$this->customerName = $customerName;
		$this->_touchedFields[self::FIELD_CUSTOMER_NAME] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomerName(): string {
		return $this->customerName;
	}

	/**
	 * @return bool
	 */
	public function hasCustomerName(): bool {
		return $this->customerName !== null;
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
