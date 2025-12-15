<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * MutableMeta DTO
 *
 * @property string $title
 * @property array<string, string|null> $meta
 */
class MutableMetaDto extends AbstractDto {

	/**
	 * @var string
	 */
	public const FIELD_TITLE = 'title';
	/**
	 * @var string
	 */
	public const FIELD_META = 'meta';

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var array<string, string|null>
	 */
	protected $meta;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'title' => [
			'name' => 'title',
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
		'meta' => [
			'name' => 'meta',
			'type' => '(string|null)[]',
			'associative' => true,
			'collectionType' => 'array',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'singularType' => 'string',
			'singularNullable' => true,
			'singularTypeHint' => 'string',
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'title' => 'title',
			'meta' => 'meta',
		],
		'dashed' => [
			'title' => 'title',
			'meta' => 'meta',
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
		'title' => 'setTitle',
		'meta' => 'setMeta',
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
		if (isset($data['title'])) {
			$this->title = $data['title'];
			$this->_touchedFields['title'] = true;
		}
		if (isset($data['meta'])) {
			$this->meta = $data['meta'];
			$this->_touchedFields['meta'] = true;
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
		if ($this->title === null) {
			$errors = [];
			if ($this->title === null) {
				$errors[] = 'title';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle(string $title) {
		$this->title = $title;
		$this->_touchedFields[static::FIELD_TITLE] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function hasTitle(): bool {
		return $this->title !== null;
	}

	/**
	 * @param array<string, string|null> $meta
	 *
	 * @return $this
	 */
	public function setMeta(array $meta) {
		$this->meta = $meta;
		$this->_touchedFields[static::FIELD_META] = true;

		return $this;
	}

	/**
	 * @return array<string, string|null>
	 */
	public function getMeta(): array {
		if ($this->meta === null) {
			return [];
		}

		return $this->meta;
	}

	/**
	 * @param string $key
	 *
	 * @return string|null
	 */
	public function getMetaValue($key): ?string {
		if (!isset($this->meta[$key])) {
			return null;
		}

		return $this->meta[$key];
	}

	/**
	 * @return bool
	 */
	public function hasMeta(): bool {
		if ($this->meta === null) {
			return false;
		}

		return count($this->meta) > 0;
	}
	/**
	 * @param string $key
	 * @param string|null $metaValue
	 * @return $this
	 */
	public function addMetaValue($key, ?string $metaValue) {
		if ($this->meta === null) {
			$this->meta = [];
		}

		$this->meta[$key] = $metaValue;
		$this->_touchedFields[static::FIELD_META] = true;

		return $this;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{title: string, meta: array<string, string>}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{title: string, meta: array<string, string>} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{title: string, meta: array<string, string>} $data
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
