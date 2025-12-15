<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto;

/**
 * Tag DTO
 *
 * @property int $id
 * @property string $name
 * @property int $weight
 *
 * @method array{id: int, name: string, weight: int} toArray(?string $type = null, ?array $fields = null, bool $touched = false)
 * @method static static createFromArray(array{id: int, name: string, weight: int} $data, bool $ignoreMissing = false, ?string $type = null)
 */
class TagDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_ID = 'id';
	/**
	 * @var string
	 */
	public const FIELD_NAME = 'name';
	/**
	 * @var string
	 */
	public const FIELD_WEIGHT = 'weight';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var int
	 */
	protected $weight;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'id' => [
			'name' => 'id',
			'type' => 'int',
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
		'name' => [
			'name' => 'name',
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
		],
		'weight' => [
			'name' => 'weight',
			'type' => 'int',
			'required' => true,
			'defaultValue' => 0,
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
			'id' => 'id',
			'name' => 'name',
			'weight' => 'weight',
		],
		'dashed' => [
			'id' => 'id',
			'name' => 'name',
			'weight' => 'weight',
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
		'id' => 'withId',
		'name' => 'withName',
		'weight' => 'withWeight',
	];


	/**
	 * Optimized setDefaults - only processes fields with default values.
	 *
	 * @return $this
	 */
	protected function setDefaults() {
		if ($this->weight === null) {
			$this->weight = 0;
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
		if ($this->id === null || $this->name === null || $this->weight === null) {
			$errors = [];
			if ($this->id === null) {
				$errors[] = 'id';
			}
			if ($this->name === null) {
				$errors[] = 'name';
			}
			if ($this->weight === null) {
				$errors[] = 'weight';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param int $id
	 *
	 * @return static
	 */
	public function withId(int $id) {
		$new = clone $this;
		$new->id = $id;
		$new->_touchedFields[static::FIELD_ID] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function hasId(): bool {
		return $this->id !== null;
	}

	/**
	 * @param string $name
	 *
	 * @return static
	 */
	public function withName(string $name) {
		$new = clone $this;
		$new->name = $name;
		$new->_touchedFields[static::FIELD_NAME] = true;

		return $new;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function hasName(): bool {
		return $this->name !== null;
	}

	/**
	 * @param int $weight
	 *
	 * @return static
	 */
	public function withWeight(int $weight) {
		$new = clone $this;
		$new->weight = $weight;
		$new->_touchedFields[static::FIELD_WEIGHT] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getWeight(): int {
		return $this->weight;
	}



	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{id: int, name: string, weight: int}
	 */
	#[\Override]
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @phpstan-ignore return.type (parent returns array, we provide shape for IDE) */
		return parent::toArray($type, $fields, $touched);
	}

	/**
	 * @param array{id: int, name: string, weight: int} $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	#[\Override]
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return parent::createFromArray($data, $ignoreMissing, $type);
	}

}
