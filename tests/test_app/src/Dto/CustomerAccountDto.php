<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

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
	protected ?int $birthYear = null;

	/**
	 * @var \Cake\I18n\DateTime|null
	 */
	protected ?\Cake\I18n\DateTime $lastLogin = null;

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
			'mapFrom' => null,
			'mapTo' => null,
			'transformFrom' => null,
			'transformTo' => null,
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
			'mapFrom' => null,
			'mapTo' => null,
			'transformFrom' => null,
			'transformTo' => null,
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
			'mapFrom' => null,
			'mapTo' => null,
			'transformFrom' => null,
			'transformTo' => null,
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
	 * Whether this DTO is immutable.
	 *
	 * @var bool
	 */
	protected const IS_IMMUTABLE = false;

	/**
	 * Whether this DTO has generated fast-path methods.
	 *
	 * @var bool
	 */
	protected const HAS_FAST_PATH = true;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'customerName' => 'setCustomerName',
		'birthYear' => 'setBirthYear',
		'lastLogin' => 'setLastLogin',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['customerName'])) {
			/** @var string $value */
			$value = $data['customerName'];
			$this->customerName = $value;
			$this->_touchedFields['customerName'] = true;
		}
		if (isset($data['birthYear'])) {
			/** @var int|null $value */
			$value = $data['birthYear'];
			$this->birthYear = $value;
			$this->_touchedFields['birthYear'] = true;
		}
		if (isset($data['lastLogin'])) {
			$value = $data['lastLogin'];
			if (!is_object($value)) {
				$value = $this->createWithConstructor('lastLogin', $value, $this->_metadata['lastLogin']);
			}
			/** @var \Cake\I18n\DateTime $value */
			$this->lastLogin = $value;
			$this->_touchedFields['lastLogin'] = true;
		}
	}

	/**
	 * Optimized toArray for default type without dynamic dispatch.
	 *
	 * @return array<string, mixed>
	 */
	protected function toArrayFast(): array {
		return [
			'customerName' => $this->customerName,
			'birthYear' => $this->birthYear,
			'lastLogin' => $this->lastLogin,
		];
	}


	/**
	 * Optimized setDefaults - only processes fields with default values.
	 *
	 * @return $this
	 */
	protected function setDefaults(): static {

		return $this;
	}

	/**
	 * Optimized validate - only checks required fields.
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	protected function validate(): void {
		if ($this->customerName === null) {
			$errors = [];
			if ($this->customerName === null) {
				$errors[] = 'customerName';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param string $customerName
	 *
	 * @return $this
	 */
	public function setCustomerName(string $customerName): static {
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
	public function setBirthYear(?int $birthYear): static {
		$this->birthYear = $birthYear;
		$this->_touchedFields[static::FIELD_BIRTH_YEAR] = true;

		return $this;
	}

	/**
	 * @param int $birthYear
	 *
	 * @return $this
	 */
	public function setBirthYearOrFail(int $birthYear): static {
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
	public function setLastLogin(?\Cake\I18n\DateTime $lastLogin): static {
		$this->lastLogin = $lastLogin;
		$this->_touchedFields[static::FIELD_LAST_LOGIN] = true;

		return $this;
	}

	/**
	 * @param \Cake\I18n\DateTime $lastLogin
	 *
	 * @return $this
	 */
	public function setLastLoginOrFail(\Cake\I18n\DateTime $lastLogin): static {
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

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{customerName: string, birthYear: int|null, lastLogin: \Cake\I18n\DateTime|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{customerName: string, birthYear: int|null, lastLogin: \Cake\I18n\DateTime|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{customerName: string, birthYear: int|null, lastLogin: \Cake\I18n\DateTime|null} $data
	 * @phpstan-param array<string, mixed> $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return static::_createFromArrayInternal($data, $ignoreMissing, $type);
	}

}
