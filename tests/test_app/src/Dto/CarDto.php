<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * Car DTO
 *
 * @property \TestApp\ValueObject\Paint|null $color
 * @property bool|null $isNew
 * @property float|null $value
 * @property int|null $distanceTravelled
 * @property array<int, string>|null $attributes
 * @property \Cake\I18n\Date|null $manufactured
 * @property \TestApp\Dto\OwnerDto|null $owner
 */
class CarDto extends AbstractDto {

	/**
	 * @var string
	 */
	public const FIELD_COLOR = 'color';

	/**
	 * @var string
	 */
	public const FIELD_IS_NEW = 'isNew';

	/**
	 * @var string
	 */
	public const FIELD_VALUE = 'value';

	/**
	 * @var string
	 */
	public const FIELD_DISTANCE_TRAVELLED = 'distanceTravelled';

	/**
	 * @var string
	 */
	public const FIELD_ATTRIBUTES = 'attributes';

	/**
	 * @var string
	 */
	public const FIELD_MANUFACTURED = 'manufactured';

	/**
	 * @var string
	 */
	public const FIELD_OWNER = 'owner';


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
	 * @var array<int, string>|null
	 */
	protected $attributes;

	/**
	 * @var \Cake\I18n\Date|null
	 */
	protected $manufactured;

	/**
	 * @var \TestApp\Dto\OwnerDto|null
	 */
	protected $owner;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'color' => [
			'name' => 'color',
			'type' => '\TestApp\ValueObject\Paint',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => 'FromArrayToArray',
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'isClass' => true,
			'enum' => null,
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
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'manufactured' => [
			'name' => 'manufactured',
			'type' => '\Cake\I18n\Date',
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
		'owner' => [
			'name' => 'owner',
			'type' => '\TestApp\Dto\OwnerDto',
			'required' => false,
			'defaultValue' => null,
			'dto' => 'Owner',
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
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
	 * Whether this DTO is immutable.
	 *
	 * @var bool
	 */
	protected const IS_IMMUTABLE = false;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'color' => 'setColor',
		'isNew' => 'setIsNew',
		'value' => 'setValue',
		'distanceTravelled' => 'setDistanceTravelled',
		'attributes' => 'setAttributes',
		'manufactured' => 'setManufactured',
		'owner' => 'setOwner',
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
		if (isset($data['color'])) {
			$value = $data['color'];
			if (is_array($value)) {
				$value = \TestApp\ValueObject\Paint::createFromArray($value);
			}
			$this->color = $value;
			$this->_touchedFields['color'] = true;
		}
		if (isset($data['isNew'])) {
			$this->isNew = $data['isNew'];
			$this->_touchedFields['isNew'] = true;
		}
		if (isset($data['value'])) {
			$this->value = $data['value'];
			$this->_touchedFields['value'] = true;
		}
		if (isset($data['distanceTravelled'])) {
			$this->distanceTravelled = $data['distanceTravelled'];
			$this->_touchedFields['distanceTravelled'] = true;
		}
		if (isset($data['attributes'])) {
			$this->attributes = $data['attributes'];
			$this->_touchedFields['attributes'] = true;
		}
		if (isset($data['manufactured'])) {
			$value = $data['manufactured'];
			if (!is_object($value)) {
				$value = $this->createWithConstructor('manufactured', $value, $this->_metadata['manufactured']);
			}
			/** @var \Cake\I18n\Date $value */
			$this->manufactured = $value;
			$this->_touchedFields['manufactured'] = true;
		}
		if (isset($data['owner'])) {
			$value = $data['owner'];
			if (is_array($value)) {
				$value = new \TestApp\Dto\OwnerDto($value, true);
			}
			$this->owner = $value;
			$this->_touchedFields['owner'] = true;
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
	 * @param \TestApp\ValueObject\Paint|null $color
	 *
	 * @return $this
	 */
	public function setColor(?\TestApp\ValueObject\Paint $color) {
		$this->color = $color;
		$this->_touchedFields[static::FIELD_COLOR] = true;

		return $this;
	}

	/**
	 * @param \TestApp\ValueObject\Paint $color
	 *
	 * @return $this
	 */
	public function setColorOrFail(\TestApp\ValueObject\Paint $color) {
		$this->color = $color;
		$this->_touchedFields[static::FIELD_COLOR] = true;

		return $this;
	}

	/**
	 * @return \TestApp\ValueObject\Paint|null
	 */
	public function getColor(): ?\TestApp\ValueObject\Paint {
		return $this->color;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\ValueObject\Paint
	 */
	public function getColorOrFail(): \TestApp\ValueObject\Paint {
		if ($this->color === null) {
			throw new \RuntimeException('Value not set for field `color` (expected to be not null)');
		}

		return $this->color;
	}

	/**
	 * @return bool
	 */
	public function hasColor(): bool {
		return $this->color !== null;
	}

	/**
	 * @param bool|null $isNew
	 *
	 * @return $this
	 */
	public function setIsNew(?bool $isNew) {
		$this->isNew = $isNew;
		$this->_touchedFields[static::FIELD_IS_NEW] = true;

		return $this;
	}

	/**
	 * @param bool $isNew
	 *
	 * @return $this
	 */
	public function setIsNewOrFail(bool $isNew) {
		$this->isNew = $isNew;
		$this->_touchedFields[static::FIELD_IS_NEW] = true;

		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function getIsNew(): ?bool {
		return $this->isNew;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return bool
	 */
	public function getIsNewOrFail(): bool {
		if ($this->isNew === null) {
			throw new \RuntimeException('Value not set for field `isNew` (expected to be not null)');
		}

		return $this->isNew;
	}

	/**
	 * @return bool
	 */
	public function hasIsNew(): bool {
		return $this->isNew !== null;
	}

	/**
	 * @param float|null $value
	 *
	 * @return $this
	 */
	public function setValue(?float $value) {
		$this->value = $value;
		$this->_touchedFields[static::FIELD_VALUE] = true;

		return $this;
	}

	/**
	 * @param float $value
	 *
	 * @return $this
	 */
	public function setValueOrFail(float $value) {
		$this->value = $value;
		$this->_touchedFields[static::FIELD_VALUE] = true;

		return $this;
	}

	/**
	 * @return float|null
	 */
	public function getValue(): ?float {
		return $this->value;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return float
	 */
	public function getValueOrFail(): float {
		if ($this->value === null) {
			throw new \RuntimeException('Value not set for field `value` (expected to be not null)');
		}

		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function hasValue(): bool {
		return $this->value !== null;
	}

	/**
	 * @param int|null $distanceTravelled
	 *
	 * @return $this
	 */
	public function setDistanceTravelled(?int $distanceTravelled) {
		$this->distanceTravelled = $distanceTravelled;
		$this->_touchedFields[static::FIELD_DISTANCE_TRAVELLED] = true;

		return $this;
	}

	/**
	 * @param int $distanceTravelled
	 *
	 * @return $this
	 */
	public function setDistanceTravelledOrFail(int $distanceTravelled) {
		$this->distanceTravelled = $distanceTravelled;
		$this->_touchedFields[static::FIELD_DISTANCE_TRAVELLED] = true;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getDistanceTravelled(): ?int {
		return $this->distanceTravelled;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return int
	 */
	public function getDistanceTravelledOrFail(): int {
		if ($this->distanceTravelled === null) {
			throw new \RuntimeException('Value not set for field `distanceTravelled` (expected to be not null)');
		}

		return $this->distanceTravelled;
	}

	/**
	 * @return bool
	 */
	public function hasDistanceTravelled(): bool {
		return $this->distanceTravelled !== null;
	}

	/**
	 * @param array<int, string>|null $attributes
	 *
	 * @return $this
	 */
	public function setAttributes(?array $attributes) {
		$this->attributes = $attributes;
		$this->_touchedFields[static::FIELD_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @param array<int, string> $attributes
	 *
	 * @return $this
	 */
	public function setAttributesOrFail(array $attributes) {
		$this->attributes = $attributes;
		$this->_touchedFields[static::FIELD_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @return array<int, string>|null
	 */
	public function getAttributes(): ?array {
		return $this->attributes;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return array<int, string>
	 */
	public function getAttributesOrFail(): array {
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
	 * @param \Cake\I18n\Date|null $manufactured
	 *
	 * @return $this
	 */
	public function setManufactured(?\Cake\I18n\Date $manufactured) {
		$this->manufactured = $manufactured;
		$this->_touchedFields[static::FIELD_MANUFACTURED] = true;

		return $this;
	}

	/**
	 * @param \Cake\I18n\Date $manufactured
	 *
	 * @return $this
	 */
	public function setManufacturedOrFail(\Cake\I18n\Date $manufactured) {
		$this->manufactured = $manufactured;
		$this->_touchedFields[static::FIELD_MANUFACTURED] = true;

		return $this;
	}

	/**
	 * @return \Cake\I18n\Date|null
	 */
	public function getManufactured(): ?\Cake\I18n\Date {
		return $this->manufactured;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \Cake\I18n\Date
	 */
	public function getManufacturedOrFail(): \Cake\I18n\Date {
		if ($this->manufactured === null) {
			throw new \RuntimeException('Value not set for field `manufactured` (expected to be not null)');
		}

		return $this->manufactured;
	}

	/**
	 * @return bool
	 */
	public function hasManufactured(): bool {
		return $this->manufactured !== null;
	}

	/**
	 * @param \TestApp\Dto\OwnerDto|null $owner
	 *
	 * @return $this
	 */
	public function setOwner(?\TestApp\Dto\OwnerDto $owner) {
		$this->owner = $owner;
		$this->_touchedFields[static::FIELD_OWNER] = true;

		return $this;
	}

	/**
	 * @param \TestApp\Dto\OwnerDto $owner
	 *
	 * @return $this
	 */
	public function setOwnerOrFail(\TestApp\Dto\OwnerDto $owner) {
		$this->owner = $owner;
		$this->_touchedFields[static::FIELD_OWNER] = true;

		return $this;
	}

	/**
	 * @return \TestApp\Dto\OwnerDto|null
	 */
	public function getOwner(): ?\TestApp\Dto\OwnerDto {
		return $this->owner;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\Dto\OwnerDto
	 */
	public function getOwnerOrFail(): \TestApp\Dto\OwnerDto {
		if ($this->owner === null) {
			throw new \RuntimeException('Value not set for field `owner` (expected to be not null)');
		}

		return $this->owner;
	}

	/**
	 * @return bool
	 */
	public function hasOwner(): bool {
		return $this->owner !== null;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null} $data
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
