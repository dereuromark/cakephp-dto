<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * EnumTest DTO
 *
 * @property \TestApp\Model\Enum\MyUnit|null $someUnit
 * @property \TestApp\Model\Enum\MyStringBacked|null $someStringBacked
 * @property \TestApp\Model\Enum\MyIntBacked|null $someIntBacked
 */
class EnumTestDto extends \CakeDto\Dto\AbstractImmutableDto {

	public const FIELD_SOME_UNIT = 'someUnit';
	public const FIELD_SOME_STRING_BACKED = 'someStringBacked';
	public const FIELD_SOME_INT_BACKED = 'someIntBacked';

	/**
	 * @var \TestApp\Model\Enum\MyUnit|null
	 */
	protected $someUnit;

	/**
	 * @var \TestApp\Model\Enum\MyStringBacked|null
	 */
	protected $someStringBacked;

	/**
	 * @var \TestApp\Model\Enum\MyIntBacked|null
	 */
	protected $someIntBacked;

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
	 * @param \TestApp\Model\Enum\MyUnit|null $someUnit
	 *
	 * @return static
	 */
	public function withSomeUnit(?\TestApp\Model\Enum\MyUnit $someUnit = null) {
		$new = clone $this;
		$new->someUnit = $someUnit;
		$new->_touchedFields[self::FIELD_SOME_UNIT] = true;

		return $new;
	}

	/**
	 * @param \TestApp\Model\Enum\MyUnit $someUnit
	 *
	 * @throws \RuntimeException If value is not present.
	 *
	 * @return static
	 */
	public function withSomeUnitOrFail(\TestApp\Model\Enum\MyUnit $someUnit) {
		$new = clone $this;
		$new->someUnit = $someUnit;
		$new->_touchedFields[self::FIELD_SOME_UNIT] = true;

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
	public function withSomeStringBacked(?\TestApp\Model\Enum\MyStringBacked $someStringBacked = null) {
		$new = clone $this;
		$new->someStringBacked = $someStringBacked;
		$new->_touchedFields[self::FIELD_SOME_STRING_BACKED] = true;

		return $new;
	}

	/**
	 * @param \TestApp\Model\Enum\MyStringBacked $someStringBacked
	 *
	 * @throws \RuntimeException If value is not present.
	 *
	 * @return static
	 */
	public function withSomeStringBackedOrFail(\TestApp\Model\Enum\MyStringBacked $someStringBacked) {
		$new = clone $this;
		$new->someStringBacked = $someStringBacked;
		$new->_touchedFields[self::FIELD_SOME_STRING_BACKED] = true;

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
	public function withSomeIntBacked(?\TestApp\Model\Enum\MyIntBacked $someIntBacked = null) {
		$new = clone $this;
		$new->someIntBacked = $someIntBacked;
		$new->_touchedFields[self::FIELD_SOME_INT_BACKED] = true;

		return $new;
	}

	/**
	 * @param \TestApp\Model\Enum\MyIntBacked $someIntBacked
	 *
	 * @throws \RuntimeException If value is not present.
	 *
	 * @return static
	 */
	public function withSomeIntBackedOrFail(\TestApp\Model\Enum\MyIntBacked $someIntBacked) {
		$new = clone $this;
		$new->someIntBacked = $someIntBacked;
		$new->_touchedFields[self::FIELD_SOME_INT_BACKED] = true;

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

}
