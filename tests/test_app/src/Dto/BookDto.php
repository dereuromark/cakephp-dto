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
 * @property \TestApp\Dto\PageDto[]|\Cake\Collection\Collection $pages
 *
 * @method array{pages: array<int, array{number: int, content: string|null}>} toArray(?string $type = null, ?array $fields = null, bool $touched = false)
 * @method static static createFromArray(array{pages: array<int, array{number: int, content: string|null}>} $data, bool $ignoreMissing = false, ?string $type = null)
 */
class BookDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_PAGES = 'pages';

	/**
	 * @var \TestApp\Dto\PageDto[]|\Cake\Collection\Collection
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
	 */
	protected const IS_IMMUTABLE = true;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'pages' => 'withPages',
	];


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
	 * @param \TestApp\Dto\PageDto[]|\Cake\Collection\Collection $pages
	 *
	 * @return static
	 */
	public function withPages(\Cake\Collection\Collection $pages) {
		$new = clone $this;
		$new->pages = $pages;
		$new->_touchedFields[static::FIELD_PAGES] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Dto\PageDto[]|\Cake\Collection\Collection
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
	public function withAddedPage(\TestApp\Dto\PageDto $page) {
		$new = clone $this;

		if ($new->pages === null) {
			$new->pages = new \Cake\Collection\Collection([]);
		}

		$new->pages = $new->pages->appendItem($page);
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
	#[\Override]
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @phpstan-ignore return.type (parent returns array, we provide shape for IDE) */
		return parent::toArray($type, $fields, $touched);
	}

	/**
	 * @param array{pages: array<int, array{number: int, content: string|null}>} $data
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
