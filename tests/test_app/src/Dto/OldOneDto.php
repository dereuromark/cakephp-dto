<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;


/**
 * OldOne DTO
 *
 * @property string|null $name
 *
 * @deprecated Yeah, sry
 */
class OldOneDto extends CarDto {

	/**
	 * @var string
	 */
	public const FIELD_NAME = 'name';


	/**
	 * @var string|null
	 */
	protected $name;

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
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'name' => 'name',
		],
		'dashed' => [
			'name' => 'name',
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
		'name' => 'setName',
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
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null, name: string|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null, name: string|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null, name: string|null} $data
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
