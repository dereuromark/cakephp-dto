<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;


/**
 * FlyingCar DTO
 *
 * @property int $maxAltitude
 * @property int $maxSpeed
 * @property array|null $complexAttributes
 */
class FlyingCarDto extends CarDto {

	/**
	 * @var string
	 */
	public const FIELD_MAX_ALTITUDE = 'maxAltitude';
	/**
	 * @var string
	 */
	public const FIELD_MAX_SPEED = 'maxSpeed';
	/**
	 * @var string
	 */
	public const FIELD_COMPLEX_ATTRIBUTES = 'complexAttributes';

	/**
	 * @var int
	 */
	protected $maxAltitude;

	/**
	 * @var int
	 */
	protected $maxSpeed;

	/**
	 * @var array|null
	 */
	protected $complexAttributes;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'maxAltitude' => [
			'name' => 'maxAltitude',
			'type' => 'int',
			'defaultValue' => 0,
			'required' => true,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'maxSpeed' => [
			'name' => 'maxSpeed',
			'type' => 'int',
			'defaultValue' => 0,
			'required' => true,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'complexAttributes' => [
			'name' => 'complexAttributes',
			'type' => 'array',
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
			'max_altitude' => 'maxAltitude',
			'max_speed' => 'maxSpeed',
			'complex_attributes' => 'complexAttributes',
		],
		'dashed' => [
			'max-altitude' => 'maxAltitude',
			'max-speed' => 'maxSpeed',
			'complex-attributes' => 'complexAttributes',
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
		'maxAltitude' => 'setMaxaltitude',
		'maxSpeed' => 'setMaxspeed',
		'complexAttributes' => 'setComplexattributes',
	];


	/**
	 * Optimized setDefaults - only processes fields with default values.
	 *
	 * @return $this
	 */
	protected function setDefaults() {
		if ($this->maxAltitude === null) {
			$this->maxAltitude = 0;
		}
		if ($this->maxSpeed === null) {
			$this->maxSpeed = 0;
		}

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
		if ($this->maxAltitude === null || $this->maxSpeed === null) {
			$errors = [];
			if ($this->maxAltitude === null) {
				$errors[] = 'maxAltitude';
			}
			if ($this->maxSpeed === null) {
				$errors[] = 'maxSpeed';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param int $maxAltitude
	 *
	 * @return $this
	 */
	public function setMaxAltitude(int $maxAltitude) {
		$this->maxAltitude = $maxAltitude;
		$this->_touchedFields[static::FIELD_MAX_ALTITUDE] = true;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getMaxAltitude(): int {
		return $this->maxAltitude;
	}


	/**
	 * @param int $maxSpeed
	 *
	 * @return $this
	 */
	public function setMaxSpeed(int $maxSpeed) {
		$this->maxSpeed = $maxSpeed;
		$this->_touchedFields[static::FIELD_MAX_SPEED] = true;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getMaxSpeed(): int {
		return $this->maxSpeed;
	}


	/**
	 * @param array|null $complexAttributes
	 *
	 * @return $this
	 */
	public function setComplexAttributes(?array $complexAttributes) {
		$this->complexAttributes = $complexAttributes;
		$this->_touchedFields[static::FIELD_COMPLEX_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @param array $complexAttributes
	 *
	 * @return $this
	 */
	public function setComplexAttributesOrFail(array $complexAttributes) {
		$this->complexAttributes = $complexAttributes;
		$this->_touchedFields[static::FIELD_COMPLEX_ATTRIBUTES] = true;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getComplexAttributes(): ?array {
		return $this->complexAttributes;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return array
	 */
	public function getComplexAttributesOrFail(): array {
		if ($this->complexAttributes === null) {
			throw new \RuntimeException('Value not set for field `complexAttributes` (expected to be not null)');
		}

		return $this->complexAttributes;
	}

	/**
	 * @return bool
	 */
	public function hasComplexAttributes(): bool {
		return $this->complexAttributes !== null;
	}


	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{maxAltitude: int, maxSpeed: int, complexAttributes: array<int, mixed>|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		return $this->_toArrayInternal($type, $fields, $touched);
	}

	/**
	 * @param array{maxAltitude: int, maxSpeed: int, complexAttributes: array<int, mixed>|null} $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return static::_createFromArrayInternal($data, $ignoreMissing, $type);
	}

}
