<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto;

/**
 * EnumTest DTO
 *
 * @property \TestApp\Model\Enum\MyUnit|null $someUnit
 * @property \TestApp\Model\Enum\MyStringBacked|null $someStringBacked
 * @property \TestApp\Model\Enum\MyIntBacked|null $someIntBacked
 */
class EnumTestDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_SOME_UNIT = 'someUnit';

	/**
	 * @var string
	 */
	public const FIELD_SOME_STRING_BACKED = 'someStringBacked';

	/**
	 * @var string
	 */
	public const FIELD_SOME_INT_BACKED = 'someIntBacked';


	/**
	 * @var \TestApp\Model\Enum\MyUnit|null
	 */
	protected ?\TestApp\Model\Enum\MyUnit $someUnit = null;

	/**
	 * @var \TestApp\Model\Enum\MyStringBacked|null
	 */
	protected ?\TestApp\Model\Enum\MyStringBacked $someStringBacked = null;

	/**
	 * @var \TestApp\Model\Enum\MyIntBacked|null
	 */
	protected ?\TestApp\Model\Enum\MyIntBacked $someIntBacked = null;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'someUnit' => [
			'name' => 'someUnit',
			'type' => '\TestApp\Model\Enum\MyUnit',
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
			'minLength' => null,
			'maxLength' => null,
			'min' => null,
			'max' => null,
			'pattern' => null,
			'lazy' => false,
			'isClass' => true,
			'enum' => 'unit',
		],
		'someStringBacked' => [
			'name' => 'someStringBacked',
			'type' => '\TestApp\Model\Enum\MyStringBacked',
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
			'minLength' => null,
			'maxLength' => null,
			'min' => null,
			'max' => null,
			'pattern' => null,
			'lazy' => false,
			'isClass' => true,
			'enum' => 'string',
		],
		'someIntBacked' => [
			'name' => 'someIntBacked',
			'type' => '\TestApp\Model\Enum\MyIntBacked',
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
			'minLength' => null,
			'maxLength' => null,
			'min' => null,
			'max' => null,
			'pattern' => null,
			'lazy' => false,
			'isClass' => true,
			'enum' => 'int',
		],
	];

	/**
	 * @var array<string, array<string, string>>
	 */
	protected array $_keyMap = [
		'underscored' => [
			'some_unit' => 'someUnit',
			'some_string_backed' => 'someStringBacked',
			'some_int_backed' => 'someIntBacked',
		],
		'dashed' => [
			'some-unit' => 'someUnit',
			'some-string-backed' => 'someStringBacked',
			'some-int-backed' => 'someIntBacked',
		],
	];

	/**
	 * Whether this DTO is immutable.
	 *
	 * @var bool
	 */
	protected const IS_IMMUTABLE = true;

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
		'someUnit' => 'withSomeUnit',
		'someStringBacked' => 'withSomeStringBacked',
		'someIntBacked' => 'withSomeIntBacked',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['someUnit'])) {
			$value = $data['someUnit'];
			if (!is_object($value)) {
				$value = $this->createEnum('someUnit', $value);
			}
			/** @var \TestApp\Model\Enum\MyUnit|null $value */
			$this->someUnit = $value;
			$this->_touchedFields['someUnit'] = true;
		}
		if (isset($data['someStringBacked'])) {
			$value = $data['someStringBacked'];
			if (!is_object($value)) {
				$value = $this->createEnum('someStringBacked', $value);
			}
			/** @var \TestApp\Model\Enum\MyStringBacked|null $value */
			$this->someStringBacked = $value;
			$this->_touchedFields['someStringBacked'] = true;
		}
		if (isset($data['someIntBacked'])) {
			$value = $data['someIntBacked'];
			if (!is_object($value)) {
				$value = $this->createEnum('someIntBacked', $value);
			}
			/** @var \TestApp\Model\Enum\MyIntBacked|null $value */
			$this->someIntBacked = $value;
			$this->_touchedFields['someIntBacked'] = true;
		}
	}

	/**
	 * Optimized toArray for default type without dynamic dispatch.
	 *
	 * @return array<string, mixed>
	 */
	protected function toArrayFast(): array {
		return [
			'someUnit' => $this->someUnit?->name,
			'someStringBacked' => $this->someStringBacked,
			'someIntBacked' => $this->someIntBacked,
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
	 * @param \TestApp\Model\Enum\MyUnit|null $someUnit
	 *
	 * @return static
	 */
	public function withSomeUnit(?\TestApp\Model\Enum\MyUnit $someUnit = null): static {
		$new = clone $this;
		$new->someUnit = $someUnit;
		$new->_touchedFields[static::FIELD_SOME_UNIT] = true;

		return $new;
	}

	/**
	 * @param \TestApp\Model\Enum\MyUnit $someUnit
	 *
	 * @return static
	 */
	public function withSomeUnitOrFail(\TestApp\Model\Enum\MyUnit $someUnit): static {
		$new = clone $this;
		$new->someUnit = $someUnit;
		$new->_touchedFields[static::FIELD_SOME_UNIT] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Model\Enum\MyUnit|null
	 */
	public function getSomeUnit(): ?\TestApp\Model\Enum\MyUnit {
		return $this->someUnit;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\Model\Enum\MyUnit
	 */
	public function getSomeUnitOrFail(): \TestApp\Model\Enum\MyUnit {
		if ($this->someUnit === null) {
			throw new \RuntimeException('Value not set for field `someUnit` (expected to be not null)');
		}

		return $this->someUnit;
	}

	/**
	 * @return bool
	 */
	public function hasSomeUnit(): bool {
		return $this->someUnit !== null;
	}

	/**
	 * @param \TestApp\Model\Enum\MyStringBacked|null $someStringBacked
	 *
	 * @return static
	 */
	public function withSomeStringBacked(?\TestApp\Model\Enum\MyStringBacked $someStringBacked = null): static {
		$new = clone $this;
		$new->someStringBacked = $someStringBacked;
		$new->_touchedFields[static::FIELD_SOME_STRING_BACKED] = true;

		return $new;
	}

	/**
	 * @param \TestApp\Model\Enum\MyStringBacked $someStringBacked
	 *
	 * @return static
	 */
	public function withSomeStringBackedOrFail(\TestApp\Model\Enum\MyStringBacked $someStringBacked): static {
		$new = clone $this;
		$new->someStringBacked = $someStringBacked;
		$new->_touchedFields[static::FIELD_SOME_STRING_BACKED] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Model\Enum\MyStringBacked|null
	 */
	public function getSomeStringBacked(): ?\TestApp\Model\Enum\MyStringBacked {
		return $this->someStringBacked;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\Model\Enum\MyStringBacked
	 */
	public function getSomeStringBackedOrFail(): \TestApp\Model\Enum\MyStringBacked {
		if ($this->someStringBacked === null) {
			throw new \RuntimeException('Value not set for field `someStringBacked` (expected to be not null)');
		}

		return $this->someStringBacked;
	}

	/**
	 * @return bool
	 */
	public function hasSomeStringBacked(): bool {
		return $this->someStringBacked !== null;
	}

	/**
	 * @param \TestApp\Model\Enum\MyIntBacked|null $someIntBacked
	 *
	 * @return static
	 */
	public function withSomeIntBacked(?\TestApp\Model\Enum\MyIntBacked $someIntBacked = null): static {
		$new = clone $this;
		$new->someIntBacked = $someIntBacked;
		$new->_touchedFields[static::FIELD_SOME_INT_BACKED] = true;

		return $new;
	}

	/**
	 * @param \TestApp\Model\Enum\MyIntBacked $someIntBacked
	 *
	 * @return static
	 */
	public function withSomeIntBackedOrFail(\TestApp\Model\Enum\MyIntBacked $someIntBacked): static {
		$new = clone $this;
		$new->someIntBacked = $someIntBacked;
		$new->_touchedFields[static::FIELD_SOME_INT_BACKED] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Model\Enum\MyIntBacked|null
	 */
	public function getSomeIntBacked(): ?\TestApp\Model\Enum\MyIntBacked {
		return $this->someIntBacked;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return \TestApp\Model\Enum\MyIntBacked
	 */
	public function getSomeIntBackedOrFail(): \TestApp\Model\Enum\MyIntBacked {
		if ($this->someIntBacked === null) {
			throw new \RuntimeException('Value not set for field `someIntBacked` (expected to be not null)');
		}

		return $this->someIntBacked;
	}

	/**
	 * @return bool
	 */
	public function hasSomeIntBacked(): bool {
		return $this->someIntBacked !== null;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{someUnit: \TestApp\Model\Enum\MyUnit|null, someStringBacked: \TestApp\Model\Enum\MyStringBacked|null, someIntBacked: \TestApp\Model\Enum\MyIntBacked|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{someUnit: \TestApp\Model\Enum\MyUnit|null, someStringBacked: \TestApp\Model\Enum\MyStringBacked|null, someIntBacked: \TestApp\Model\Enum\MyIntBacked|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{someUnit: \TestApp\Model\Enum\MyUnit|null, someStringBacked: \TestApp\Model\Enum\MyStringBacked|null, someIntBacked: \TestApp\Model\Enum\MyIntBacked|null} $data
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
