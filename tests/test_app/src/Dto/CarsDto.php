<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * Cars DTO
 *
 * @property \ArrayObject<string, \TestApp\Dto\CarDto> $cars
 */
class CarsDto extends AbstractDto {

	/**
	 * @var string
	 */
	public const FIELD_CARS = 'cars';


	/**
	 * @var \ArrayObject<string, \TestApp\Dto\CarDto>
	 */
	protected $cars;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'cars' => [
			'name' => 'cars',
			'type' => '\TestApp\Dto\CarDto[]|\ArrayObject',
			'associative' => true,
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => '\ArrayObject',
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'singularType' => '\TestApp\Dto\CarDto',
			'singularNullable' => false,
			'singularTypeHint' => '\TestApp\Dto\CarDto',
		],
	];

	/**
	 * @var array<string, array<string, string>>
	 */
	protected array $_keyMap = [
		'underscored' => [
			'cars' => 'cars',
		],
		'dashed' => [
			'cars' => 'cars',
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
		'cars' => 'setCars',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['cars'])) {
			$items = [];
			foreach ($data['cars'] as $item) {
				if (is_array($item)) {
					$item = new \TestApp\Dto\CarDto($item, true);
				}
				$items[] = $item;
			}
			/** @var \TestApp\Dto\CarDto[]|\ArrayObject $collection */
			$collection = new \ArrayObject($items);
			$this->cars = $collection;
			$this->_touchedFields['cars'] = true;
		}
	}

	/**
	 * Optimized toArray for default type without dynamic dispatch.
	 *
	 * @return array<string, mixed>
	 */
	protected function toArrayFast(): array {
		return [
			'cars' => $this->cars !== null ? (static function (\Traversable $c): array {
				$r = [];
				foreach ($c as $k => $v) {
					$r[$k] = $v->toArray();
				}
				return $r;
			})($this->cars) : [],
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
	}


	/**
	 * @param \ArrayObject<string, \TestApp\Dto\CarDto> $cars
	 *
	 * @return $this
	 */
	public function setCars(\ArrayObject $cars): static {
		$this->cars = $cars;
		$this->_touchedFields[static::FIELD_CARS] = true;

		return $this;
	}

	/**
	 * @return \ArrayObject<string, \TestApp\Dto\CarDto>
	 */
	public function getCars(): \ArrayObject {
		if ($this->cars === null) {
			return new \ArrayObject([]);
		}

		return $this->cars;
	}

	/**
	 * @param string $key
	 *
	 * @return \TestApp\Dto\CarDto
	 *
	 * @throws \RuntimeException If value with this key is not set.
	 */
	public function getCar(string $key): \TestApp\Dto\CarDto {
		if (!isset($this->cars[$key])) {
			throw new \RuntimeException(sprintf('Value not set for field `cars` and key `%s` (expected to be not null)', $key));
		}

		return $this->cars[$key];
	}

	/**
	 * @return bool
	 */
	public function hasCars(): bool {
		if ($this->cars === null) {
			return false;
		}

		return $this->cars->count() > 0;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasCar(string $key): bool {
		return isset($this->cars[$key]);
	}

	/**
	 * @param string $key
	 * @param \TestApp\Dto\CarDto $car
	 * @return $this
	 */
	public function addCar(string $key, \TestApp\Dto\CarDto $car): static {
		if ($this->cars === null) {
			$this->cars = new \ArrayObject([]);
		}

		$this->cars[$key] = $car;
		$this->_touchedFields[static::FIELD_CARS] = true;

		return $this;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{cars: array<string, array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null}>}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{cars: array<string, array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null}>} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{cars: array<string, array{color: \TestApp\ValueObject\Paint|null, isNew: bool|null, value: float|null, distanceTravelled: int|null, attributes: array<int, mixed>|null, manufactured: \Cake\I18n\Date|null, owner: array{name: string|null, insuranceProvider: string|null, attributes: \TestApp\ValueObject\KeyValuePair|null, birthday: \TestApp\ValueObject\Birthday|null}|null}>} $data
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
