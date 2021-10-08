<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Owner DTO
 *
 * @property string|null $name
 * @property string|null $insuranceProvider
 * @property \TestApp\ValueObject\KeyValuePair|null $attributes
 * @property \TestApp\ValueObject\Birthday|null $birthday
 */
class OwnerDto extends \CakeDto\Dto\AbstractDto {

	public const FIELD_NAME = 'name';
	public const FIELD_INSURANCE_PROVIDER = 'insuranceProvider';
	public const FIELD_ATTRIBUTES = 'attributes';
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
			'serialize' => null,
			'factory' => null,
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
			'isClass' => true,
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
			'isClass' => true,
		],
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
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
		$this->_touchedFields[self::FIELD_INSURANCE_PROVIDER] = true;

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
		$this->_touchedFields[self::FIELD_ATTRIBUTES] = true;

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
		$this->_touchedFields[self::FIELD_BIRTHDAY] = true;

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

}
