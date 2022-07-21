<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Article DTO
 *
 * @property int $id
 * @property \TestApp\Dto\AuthorDto $author
 * @property string $title
 * @property \Cake\I18n\FrozenDate $created
 * @property \TestApp\Dto\TagDto[] $tags
 * @property string[] $meta
 */
class ArticleDto extends \CakeDto\Dto\AbstractImmutableDto {

	public const FIELD_ID = 'id';
	public const FIELD_AUTHOR = 'author';
	public const FIELD_TITLE = 'title';
	public const FIELD_CREATED = 'created';
	public const FIELD_TAGS = 'tags';
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
	 * @var \Cake\I18n\FrozenDate
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
	protected $_metadata = [
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
		],
		'created' => [
			'name' => 'created',
			'type' => '\Cake\I18n\FrozenDate',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'isClass' => true,
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
			'singularType' => 'string',
			'singularNullable' => false,
			'singularTypeHint' => 'string',
		],
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected $_keyMap = [
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
	 * @param int $id
	 *
	 * @return static
	 */
	public function withId(int $id) {
		$new = clone $this;
		$new->id = $id;
		$new->_touchedFields[self::FIELD_ID] = true;

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
		$new->_touchedFields[self::FIELD_AUTHOR] = true;

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
		$new->_touchedFields[self::FIELD_TITLE] = true;

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
	 * @param \Cake\I18n\FrozenDate $created
	 *
	 * @return static
	 */
	public function withCreated(\Cake\I18n\FrozenDate $created) {
		$new = clone $this;
		$new->created = $created;
		$new->_touchedFields[self::FIELD_CREATED] = true;

		return $new;
	}

	/**
	 * @return \Cake\I18n\FrozenDate
	 */
	public function getCreated(): \Cake\I18n\FrozenDate {
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
		$new->_touchedFields[self::FIELD_TAGS] = true;

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
		$new->_touchedFields[self::FIELD_TAGS] = true;

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
		$new->_touchedFields[self::FIELD_META] = true;

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
		$new->_touchedFields[self::FIELD_META] = true;

		return $new;
	}

}
