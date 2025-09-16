<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use CakeDto\Dto\AbstractDto;

/**
 * CustomerAccount DTO
 *
 * @property string $customerName
 * @property int|null $birthYear
 * @property \Cake\I18n\DateTime|null $lastLogin
 */
class CustomerAccountDto extends AbstractDto {

	/**
	 * @var string
	 */
	public const FIELD_CUSTOMER_NAME = 'customerName';
	/**
	 * @var string
	 */
	public const FIELD_BIRTH_YEAR = 'birthYear';
	/**
	 * @var string
	 */
	public const FIELD_LAST_LOGIN = 'lastLogin';

	/**
	 * @var string
	 */
	protected $customerName;

	/**
	 * @var int|null
	 */
	protected $birthYear;

	/**
	 * @var \Cake\I18n\DateTime|null
	 */
	protected $lastLogin;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'customerName' => [
			'name' => 'customerName',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
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
			'serialize' => null,
			'factory' => null,
		],
		'lastLogin' => [
			'name' => 'lastLogin',
			'type' => '\Cake\I18n\DateTime',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'isClass' => true,
			'enum' => null,
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'customer_name' => 'customerName',
			'birth_year' => 'birthYear',
			'last_login' => 'lastLogin',
		],
		'dashed' => [
			'customer-name' => 'customerName',
			'birth-year' => 'birthYear',
			'last-login' => 'lastLogin',
		],
	];

	/**
	 * @param string $customerName
	 *
	 * @return $this
	 */
	public function setCustomerName(string $customerName) {
		$this->customerName = $customerName;
		$this->_touchedFields[static::FIELD_CUSTOMER_NAME] = true;

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
		$this->_touchedFields[static::FIELD_BIRTH_YEAR] = true;

		return $this;
	}

	/**
	 * @param int $birthYear
	 *
	 * @return $this
	 */
	public function setBirthYearOrFail(int $birthYear) {
		$this->birthYear = $birthYear;
		$this->_touchedFields[static::FIELD_BIRTH_YEAR] = true;

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
		if ($this->birthYear === null) {
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

	/**
	 * @param \Cake\I18n\DateTime|null $lastLogin
	 *
	 * @return $this
	 */
	public function setLastLogin(?\Cake\I18n\DateTime $lastLogin) {
		$this->lastLogin = $lastLogin;
		$this->_touchedFields[static::FIELD_LAST_LOGIN] = true;

		return $this;
	}

	/**
	 * @param \Cake\I18n\DateTime $lastLogin
	 *
	 * @return $this
	 */
	public function setLastLoginOrFail(\Cake\I18n\DateTime $lastLogin) {
		$this->lastLogin = $lastLogin;
		$this->_touchedFields[static::FIELD_LAST_LOGIN] = true;

		return $this;
	}

	/**
	 * @return \Cake\I18n\DateTime|null
	 */
	public function getLastLogin(): ?\Cake\I18n\DateTime {
		return $this->lastLogin;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \Cake\I18n\DateTime
	 */
	public function getLastLoginOrFail(): \Cake\I18n\DateTime {
		if ($this->lastLogin === null) {
			throw new \RuntimeException('Value not set for field `lastLogin` (expected to be not null)');
		}

		return $this->lastLogin;
	}

	/**
	 * @return bool
	 */
	public function hasLastLogin(): bool {
		return $this->lastLogin !== null;
	}

}
