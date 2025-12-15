<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto;

/**
 * Article DTO
 *
 * @property int $id
 * @property \TestApp\Dto\AuthorDto $author
 * @property string $title
 * @property \Cake\I18n\Date $created
 * @property \TestApp\Dto\TagDto[] $tags
 * @property string[] $meta
 */
class ArticleDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_ID = 'id';
	/**
	 * @var string
	 */
	public const FIELD_AUTHOR = 'author';
	/**
	 * @var string
	 */
	public const FIELD_TITLE = 'title';
	/**
	 * @var string
	 */
	public const FIELD_CREATED = 'created';
	/**
	 * @var string
	 */
	public const FIELD_TAGS = 'tags';
	/**
	 * @var string
	 */
	public const FIELD_META = 'meta';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var \TestApp\Dto\AuthorDto
	 */
	protected $author;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var \Cake\I18n\Date
	 */
	protected $created;

	/**
	 * @var \TestApp\Dto\TagDto[]
	 */
	protected $tags;

	/**
	 * @var string[]
	 */
	protected $meta;

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
		'author' => [
			'name' => 'author',
			'type' => '\TestApp\Dto\AuthorDto',
			'required' => true,
			'defaultValue' => null,
			'dto' => 'Author',
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
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
		'created' => [
			'name' => 'created',
			'type' => '\Cake\I18n\Date',
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
			'isClass' => true,
			'enum' => null,
		],
		'tags' => [
			'name' => 'tags',
			'type' => '\TestApp\Dto\TagDto[]',
			'collectionType' => 'array',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'singularType' => '\TestApp\Dto\TagDto',
			'singularNullable' => false,
			'singularTypeHint' => '\TestApp\Dto\TagDto',
		],
		'meta' => [
			'name' => 'meta',
			'type' => 'string[]',
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
			'singularNullable' => false,
			'singularTypeHint' => 'string',
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
			'id' => 'id',
			'author' => 'author',
			'title' => 'title',
			'created' => 'created',
			'tags' => 'tags',
			'meta' => 'meta',
		],
		'dashed' => [
			'id' => 'id',
			'author' => 'author',
			'title' => 'title',
			'created' => 'created',
			'tags' => 'tags',
			'meta' => 'meta',
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
		'author' => 'withAuthor',
		'title' => 'withTitle',
		'created' => 'withCreated',
		'tags' => 'withTags',
		'meta' => 'withMeta',
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
		if ($this->id === null || $this->author === null || $this->title === null || $this->created === null) {
			$errors = [];
			if ($this->id === null) {
				$errors[] = 'id';
			}
			if ($this->author === null) {
				$errors[] = 'author';
			}
			if ($this->title === null) {
				$errors[] = 'title';
			}
			if ($this->created === null) {
				$errors[] = 'created';
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
	 * @param \TestApp\Dto\AuthorDto $author
	 *
	 * @return static
	 */
	public function withAuthor(\TestApp\Dto\AuthorDto $author) {
		$new = clone $this;
		$new->author = $author;
		$new->_touchedFields[static::FIELD_AUTHOR] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Dto\AuthorDto
	 */
	public function getAuthor(): \TestApp\Dto\AuthorDto {
		return $this->author;
	}

	/**
	 * @return bool
	 */
	public function hasAuthor(): bool {
		return $this->author !== null;
	}

	/**
	 * @param string $title
	 *
	 * @return static
	 */
	public function withTitle(string $title) {
		$new = clone $this;
		$new->title = $title;
		$new->_touchedFields[static::FIELD_TITLE] = true;

		return $new;
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
	 * @param \Cake\I18n\Date $created
	 *
	 * @return static
	 */
	public function withCreated(\Cake\I18n\Date $created) {
		$new = clone $this;
		$new->created = $created;
		$new->_touchedFields[static::FIELD_CREATED] = true;

		return $new;
	}

	/**
	 * @return \Cake\I18n\Date
	 */
	public function getCreated(): \Cake\I18n\Date {
		return $this->created;
	}

	/**
	 * @return bool
	 */
	public function hasCreated(): bool {
		return $this->created !== null;
	}

	/**
	 * @param \TestApp\Dto\TagDto[] $tags
	 *
	 * @return static
	 */
	public function withTags(array $tags) {
		$new = clone $this;
		$new->tags = $tags;
		$new->_touchedFields[static::FIELD_TAGS] = true;

		return $new;
	}

	/**
	 * @return \TestApp\Dto\TagDto[]
	 */
	public function getTags(): array {
		if ($this->tags === null) {
			return [];
		}

		return $this->tags;
	}

	/**
	 * @return bool
	 */
	public function hasTags(): bool {
		if ($this->tags === null) {
			return false;
		}

		return count($this->tags) > 0;
	}
	/**
	 * @param \TestApp\Dto\TagDto $tag
	 * @return static
	 */
	public function withAddedTag(\TestApp\Dto\TagDto $tag) {
		$new = clone $this;

		if ($new->tags === null) {
			$new->tags = [];
		}

		$new->tags[] = $tag;
		$new->_touchedFields[static::FIELD_TAGS] = true;

		return $new;
	}

	/**
	 * @param string[] $meta
	 *
	 * @return static
	 */
	public function withMeta(array $meta) {
		$new = clone $this;
		$new->meta = $meta;
		$new->_touchedFields[static::FIELD_META] = true;

		return $new;
	}

	/**
	 * @return string[]
	 */
	public function getMeta(): array {
		if ($this->meta === null) {
			return [];
		}

		return $this->meta;
	}

	/**
	 * @param string|int $key
	 *
	 * @return string
	 *
	 * @throws \RuntimeException If value with this key is not set.
	 */
	public function getMetaValue($key): string {
		if (!isset($this->meta[$key])) {
			throw new \RuntimeException(sprintf('Value not set for field `meta` and key `%s` (expected to be not null)', $key));
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
	 * @param string|int $key
	 * @return bool
	 */
	public function hasMetaValue($key): bool {
		return isset($this->meta[$key]);
	}

	/**
	 * @param string|int $key
	 * @param string $metaValue
	 * @return static
	 */
	public function withAddedMetaValue($key, string $metaValue) {
		$new = clone $this;

		if ($new->meta === null) {
			$new->meta = [];
		}

		$new->meta[$key] = $metaValue;
		$new->_touchedFields[static::FIELD_META] = true;

		return $new;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{id: int, author: array{id: int, name: string, email: string|null}, title: string, created: \Cake\I18n\Date, tags: array<int, array{id: int, name: string, weight: int}>, meta: array<string, string>}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{id: int, author: array{id: int, name: string, email: string|null}, title: string, created: \Cake\I18n\Date, tags: array<int, array{id: int, name: string, weight: int}>, meta: array<string, string>} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{id: int, author: array{id: int, name: string, email: string|null}, title: string, created: \Cake\I18n\Date, tags: array<int, array{id: int, name: string, weight: int}>, meta: array<string, string>} $data
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
