<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto;

/**
 * Book DTO
 *
 * @property \Cake\Collection\Collection<int, \TestApp\Dto\PageDto> $pages
 */
class BookDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_PAGES = 'pages';


	/**
	 * @var \Cake\Collection\Collection<int, \TestApp\Dto\PageDto>
	 */
	protected $pages;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'pages' => [
			'name' => 'pages',
			'type' => '\TestApp\Dto\PageDto[]|\Cake\Collection\Collection',
			'collectionType' => '\Cake\Collection\Collection',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'singularType' => '\TestApp\Dto\PageDto',
			'singularNullable' => false,
			'singularTypeHint' => '\TestApp\Dto\PageDto',
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'pages' => 'pages',
		],
		'dashed' => [
			'pages' => 'pages',
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
		'pages' => 'withPages',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['pages'])) {
			$items = [];
			foreach ($data['pages'] as $item) {
				if (is_array($item)) {
					$item = new \TestApp\Dto\PageDto($item, true);
				}
				$items[] = $item;
			}
			/** @var \TestApp\Dto\PageDto[]|\Cake\Collection\Collection $collection */
			$collection = new \Cake\Collection\Collection($items);
			$this->pages = $collection;
			$this->_touchedFields['pages'] = true;
		}
	}

	/**
	 * Optimized toArray for default type without dynamic dispatch.
	 *
	 * @return array<string, mixed>
	 */
	protected function toArrayFast(): array {
		return [
			'pages' => $this->pages !== null ? (static function (\Traversable $c): array {
				$r = [];
				foreach ($c as $k => $v) {
					$r[$k] = $v->toArray();
				}
				return $r;
			})($this->pages) : [],
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
	 * @param \Cake\Collection\Collection<int, \TestApp\Dto\PageDto> $pages
	 *
	 * @return static
	 */
	public function withPages(\Cake\Collection\Collection $pages): static {
		$new = clone $this;
		$new->pages = $pages;
		$new->_touchedFields[static::FIELD_PAGES] = true;

		return $new;
	}

	/**
	 * @return \Cake\Collection\Collection<int, \TestApp\Dto\PageDto>
	 */
	public function getPages(): \Cake\Collection\Collection {
		if ($this->pages === null) {
			return new \Cake\Collection\Collection([]);
		}

		return $this->pages;
	}

	/**
	 * @return bool
	 */
	public function hasPages(): bool {
		if ($this->pages === null) {
			return false;
		}

		return $this->pages->count() > 0;
	}
	/**
	 * @param \TestApp\Dto\PageDto $page
	 * @return static
	 */
	public function withAddedPage(\TestApp\Dto\PageDto $page): static {
		$new = clone $this;

		if ($new->pages === null) {
			$new->pages = new \Cake\Collection\Collection([]);
		}

		/** @var \Cake\Collection\Collection $collection */
		$collection = $new->pages->appendItem($page);
		$new->pages = $collection;
		$new->_touchedFields[static::FIELD_PAGES] = true;

		return $new;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{pages: array<int, array{number: int, content: string|null}>}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{pages: array<int, array{number: int, content: string|null}>} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{pages: array<int, array{number: int, content: string|null}>} $data
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
