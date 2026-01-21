<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * Owner DTO
 *
 * @property string|null $name
 * @property string|null $insuranceProvider
 * @property \TestApp\ValueObject\KeyValuePair|null $attributes
 * @property \TestApp\ValueObject\Birthday|null $birthday
 */
class OwnerDto extends AbstractDto {

	/**
	 * @var string
	 */
	public const FIELD_NAME = 'name';
	/**
	 * @var string
	 */
	public const FIELD_INSURANCE_PROVIDER = 'insuranceProvider';
	/**
	 * @var string
	 */
	public const FIELD_ATTRIBUTES = 'attributes';
	/**
	 * @var string
	 */
	public const FIELD_BIRTHDAY = 'birthday';

	/**
	 * @var string|null
	 */
	protected $name;

	/**
	 * @var string|null
	 */
	protected $insuranceProvider;

	/**
	 * @var \TestApp\ValueObject\KeyValuePair|null
	 */
	protected $attributes;

	/**
	 * @var \TestApp\ValueObject\Birthday|null
	 */
	protected $birthday;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'name' => [
			'name' => 'name',
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
		'insuranceProvider' => [
			'name' => 'insuranceProvider',
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
		'attributes' => [
			'name' => 'attributes',
			'type' => '\TestApp\ValueObject\KeyValuePair',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => 'array',
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'isClass' => true,
			'enum' => null,
		],
		'birthday' => [
			'name' => 'birthday',
			'type' => '\TestApp\ValueObject\Birthday',
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
			'isClass' => true,
			'enum' => null,
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'name' => 'name',
			'insurance_provider' => 'insuranceProvider',
			'attributes' => 'attributes',
			'birthday' => 'birthday',
		],
		'dashed' => [
			'name' => 'name',
			'insurance-provider' => 'insuranceProvider',
			'attributes' => 'attributes',
			'birthday' => 'birthday',
		],
	];

	/**
	 * Whether this DTO is immutable.
	 */
	protected const IS_IMMUTABLE = false;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'name' => 'setName',
		'insuranceProvider' => 'setInsuranceProvider',
		'attributes' => 'setAttributes',
		'birthday' => 'setBirthday',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * This method is only called in lenient mode (ignoreMissing=true),
	 * where unknown fields are silently ignored.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['name'])) {
			$this->name = $data['name'];
			$this->_touchedFields['name'] = true;
		}
		if (isset($data['insuranceProvider'])) {
			$this->insuranceProvider = $data['insuranceProvider'];
			$this->_touchedFields['insuranceProvider'] = true;
		}
		if (isset($data['attributes'])) {
			$value = $data['attributes'];
			if (is_array($value)) {
				$value = \TestApp\ValueObject\KeyValuePair::createFromArray($value);
			}
			$this->attributes = $value;
			$this->_touchedFields['attributes'] = true;
		}
		if (isset($data['birthday'])) {
			$value = $data['birthday'];
			if (!is_object($value)) {
				$value = $this->createWithConstructor('birthday', $value, $this->_metadata['birthday']);
			}
			/** @var \TestApp\ValueObject\Birthday $value */
			$this->birthday = $value;
			$this->_touchedFields['birthday'] = true;
		}
	}


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
	}


	/**
	 * @param string|null $name
	 *
	 * @return $this
	 */
	public function setName(?string $name) {
		$this->name = $name;
		$this->_touchedFields[static::FIELD_NAME] = true;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setNameOrFail(string $name) {
		$this->name = $name;
		$this->_touchedFields[static::FIELD_NAME] = true;

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
		if ($this->name === null) {
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
	 * @param string|null $insuranceProvider
	 *
	 * @return $this
	 */
	public function setInsuranceProvider(?string $insuranceProvider) {
		$this->insuranceProvider = $insuranceProvider;
		$this->_touchedFields[static::FIELD_INSURANCE_PROVIDER] = true;

		return $this;
	}

	/**
	 * @param string $insuranceProvider
	 *
	 * @return $this
	 */
	public function setInsuranceProviderOrFail(string $insuranceProvider) {
		$this->insuranceProvider = $insuranceProvider;
		$this->_touchedFields[static::FIELD_INSURANCE_PROVIDER] = true;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getInsuranceProvider(): ?string {
		return $this->insuranceProvider;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getInsuranceProviderOrFail(): string {
		if ($this->insuranceProvider === null) {
			throw new \RuntimeException('Value not set for field `insuranceProvider` (expected to be not null)');
		}

		return $this->insuranceProvider;
	}

	/**
	 * @return bool
	 */
	public function hasInsuranceProvider(): bool {
		return $this->insuranceProvider !== null;
	}

	/**
	 * @param \TestApp\ValueObject\KeyValuePair|null $attributes
	 *
	 * @return $this
	 */
	public function setAttributes(?\TestApp\ValueObject\KeyValuePair $attributes) {
		$this->attributes = $attributes;
		$this->_touchedFields[static::FIELD_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @param \TestApp\ValueObject\KeyValuePair $attributes
	 *
	 * @return $this
	 */
	public function setAttributesOrFail(\TestApp\ValueObject\KeyValuePair $attributes) {
		$this->attributes = $attributes;
		$this->_touchedFields[static::FIELD_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @return \TestApp\ValueObject\KeyValuePair|null
	 */
	public function getAttributes(): ?\TestApp\ValueObject\KeyValuePair {
		return $this->attributes;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\ValueObject\KeyValuePair
	 */
	public function getAttributesOrFail(): \TestApp\ValueObject\KeyValuePair {
		if ($this->attributes === null) {
			throw new \RuntimeException('Value not set for field `attributes` (expected to be not null)');
		}

		return $this->attributes;
	}

	/**
	 * @return bool
	 */
	public function hasAttributes(): bool {
		return $this->attributes !== null;
	}

	/**
	 * @param \TestApp\ValueObject\Birthday|null $birthday
	 *
	 * @return $this
	 */
	public function setBirthday(?\TestApp\ValueObject\Birthday $birthday) {
		$this->birthday = $birthday;
		$this->_touchedFields[static::FIELD_BIRTHDAY] = true;

		return $this;
	}

	/**
	 * @param \TestApp\ValueObject\Birthday $birthday
	 *
	 * @return $this
	 */
	public function setBirthdayOrFail(\TestApp\ValueObject\Birthday $birthday) {
		$this->birthday = $birthday;
		$this->_touchedFields[static::FIELD_BIRTHDAY] = true;

		return $this;
	}

	/**
	 * @return \TestApp\ValueObject\Birthday|null
	 */
	public function getBirthday(): ?\TestApp\ValueObject\Birthday {
		return $this->birthday;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\ValueObject\Birthday
	 */
	public function getBirthdayOrFail(): \TestApp\ValueObject\Birthday {
		if ($this->birthday === null) {
			throw new \RuntimeException('Value not set for field `birthday` (expected to be not null)');
		}

		return $this->birthday;
	}

	/**
	 * @return bool
	 */
	public function hasBirthday(): bool {
		return $this->birthday !== null;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null} $data
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
