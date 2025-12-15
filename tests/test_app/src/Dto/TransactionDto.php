<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto;

/**
 * Transaction DTO
 *
 * @property \TestApp\Dto\CustomerAccountDto $customerAccount
 * @property float $value
 * @property string|null $comment
 * @property \Cake\I18n\Date $created
 */
class TransactionDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_CUSTOMER_ACCOUNT = 'customerAccount';
	/**
	 * @var string
	 */
	public const FIELD_VALUE = 'value';
	/**
	 * @var string
	 */
	public const FIELD_COMMENT = 'comment';
	/**
	 * @var string
	 */
	public const FIELD_CREATED = 'created';

	/**
	 * @var \TestApp\Dto\CustomerAccountDto
	 */
	protected $customerAccount;

	/**
	 * @var float
	 */
	protected $value;

	/**
	 * @var string|null
	 */
	protected $comment;

	/**
	 * @var \Cake\I18n\Date
	 */
	protected $created;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'customerAccount' => [
			'name' => 'customerAccount',
			'type' => '\TestApp\Dto\CustomerAccountDto',
			'required' => true,
			'defaultValue' => null,
			'dto' => 'CustomerAccount',
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'value' => [
			'name' => 'value',
			'type' => 'float',
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
		],
		'comment' => [
			'name' => 'comment',
			'type' => 'string',
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
		],
		'created' => [
			'name' => 'created',
			'type' => '\Cake\I18n\Date',
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
			'isClass' => true,
			'enum' => null,
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'customer_account' => 'customerAccount',
			'value' => 'value',
			'comment' => 'comment',
			'created' => 'created',
		],
		'dashed' => [
			'customer-account' => 'customerAccount',
			'value' => 'value',
			'comment' => 'comment',
			'created' => 'created',
		],
	];

	/**
	 * Whether this DTO is immutable.
	 */
	protected const IS_IMMUTABLE = true;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'customerAccount' => 'withCustomeraccount',
		'value' => 'withValue',
		'comment' => 'withComment',
		'created' => 'withCreated',
	];


	/**
	 * Optimized setDefaults - only processes fields with default values.
	 *
	 * @return $this
	 */
	protected function setDefaults() {

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
		if ($this->customerAccount === null || $this->value === null || $this->created === null) {
			$errors = [];
			if ($this->customerAccount === null) {
				$errors[] = 'customerAccount';
			}
			if ($this->value === null) {
				$errors[] = 'value';
			}
			if ($this->created === null) {
				$errors[] = 'created';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param \TestApp\Dto\CustomerAccountDto $customerAccount
	 *
	 * @return static
	 */
	public function withCustomerAccount(\TestApp\Dto\CustomerAccountDto $customerAccount) {
		$new = clone $this;
		$new->customerAccount = $customerAccount;
		$new->_touchedFields[static::FIELD_CUSTOMER_ACCOUNT] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Dto\CustomerAccountDto
	 */
	public function getCustomerAccount(): \TestApp\Dto\CustomerAccountDto {
		return $this->customerAccount;
	}

	/**
	 * @return bool
	 */
	public function hasCustomerAccount(): bool {
		return $this->customerAccount !== null;
	}

	/**
	 * @param float $value
	 *
	 * @return static
	 */
	public function withValue(float $value) {
		$new = clone $this;
		$new->value = $value;
		$new->_touchedFields[static::FIELD_VALUE] = true;

		return $new;
	}

	/**
	 * @return float
	 */
	public function getValue(): float {
		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function hasValue(): bool {
		return $this->value !== null;
	}

	/**
	 * @param string|null $comment
	 *
	 * @return static
	 */
	public function withComment(?string $comment = null) {
		$new = clone $this;
		$new->comment = $comment;
		$new->_touchedFields[static::FIELD_COMMENT] = true;

		return $new;
	}

	/**
	 * @param string $comment
	 *
	 * @return static
	 */
	public function withCommentOrFail(string $comment) {
		$new = clone $this;
		$new->comment = $comment;
		$new->_touchedFields[static::FIELD_COMMENT] = true;

		return $new;
	}

	/**
	 * @return string|null
	 */
	public function getComment(): ?string {
		return $this->comment;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getCommentOrFail(): string {
		if ($this->comment === null) {
			throw new \RuntimeException('Value not set for field `comment` (expected to be not null)');
		}

		return $this->comment;
	}

	/**
	 * @return bool
	 */
	public function hasComment(): bool {
		return $this->comment !== null;
	}

	/**
	 * @param \Cake\I18n\Date $created
	 *
	 * @return static
	 */
	public function withCreated(\Cake\I18n\Date $created) {
		$new = clone $this;
		$new->created = $created;
		$new->_touchedFields[static::FIELD_CREATED] = true;

		return $new;
	}

	/**
	 * @return \Cake\I18n\Date
	 */
	public function getCreated(): \Cake\I18n\Date {
		return $this->created;
	}

	/**
	 * @return bool
	 */
	public function hasCreated(): bool {
		return $this->created !== null;
	}


	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{customerAccount: array{customerName: string, birthYear: int|null, lastLogin: \Cake\I18n\DateTime|null}, value: float, comment: string|null, created: \Cake\I18n\Date}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		return $this->_toArrayInternal($type, $fields, $touched);
	}

	/**
	 * @param array{customerAccount: array{customerName: string, birthYear: int|null, lastLogin: \Cake\I18n\DateTime|null}, value: float, comment: string|null, created: \Cake\I18n\Date} $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return static::_createFromArrayInternal($data, $ignoreMissing, $type);
	}

}
