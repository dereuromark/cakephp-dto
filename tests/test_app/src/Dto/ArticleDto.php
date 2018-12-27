<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Article DTO
 */
class ArticleDto extends \CakeDto\Dto\AbstractImmutableDto {

	const FIELD_ID = 'id';
	const FIELD_AUTHOR = 'author';
	const FIELD_TITLE = 'title';
	const FIELD_CREATED = 'created';
	const FIELD_TAGS = 'tags';
	const FIELD_META = 'meta';

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
	 * @var array
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
			'serializable' => false,
			'toArray' => false,
		],
		'author' => [
			'name' => 'author',
			'type' => '\TestApp\Dto\AuthorDto',
			'required' => true,
			'defaultValue' => null,
			'dto' => 'Author',
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
		'title' => [
			'name' => 'title',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
		'created' => [
			'name' => 'created',
			'type' => '\Cake\I18n\FrozenDate',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
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
			'serializable' => false,
			'toArray' => false,
			'singularType' => '\TestApp\Dto\TagDto',
		],
		'meta' => [
			'name' => 'meta',
			'type' => 'string[]',
			'associative' => true,
			'collectionType' => 'array',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'serializable' => false,
			'toArray' => false,
			'singularType' => 'string',
		],
	];

	/**
	* @var array
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
	public function withId($id) {
		$new = clone $this;
		$new->id = $id;
		$new->_touchedFields[self::FIELD_ID] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function hasId() {
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
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * @return bool
	 */
	public function hasAuthor() {
		return $this->author !== null;
	}

	/**
	 * @param string $title
	 *
	 * @return static
	 */
	public function withTitle($title) {
		$new = clone $this;
		$new->title = $title;
		$new->_touchedFields[self::FIELD_TITLE] = true;

		return $new;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function hasTitle() {
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
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @return bool
	 */
	public function hasCreated() {
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
	public function getTags() {
		if ($this->tags === null) {
			return [];
		}

		return $this->tags;
	}

	/**
	 * @return bool
	 */
	public function hasTags() {
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

		if (!isset($new->tags)) {
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
	public function getMeta() {
		if ($this->meta === null) {
			return [];
		}

		return $this->meta;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 *
	 * @throws \RuntimeException If value with this key is not set.
	 */
	public function getMetaValue($key) {
		if (!isset($this->meta[$key])) {
			throw new \RuntimeException(sprintf('Value not set for field `meta` and key `%s` (expected to be not null)', $key));
		}

		return $this->meta[$key];
	}

	/**
	 * @return bool
	 */
	public function hasMeta() {
		if ($this->meta === null) {
			return false;
		}

		return count($this->meta) > 0;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasMetaValue($key) {
		return isset($this->meta[$key]);
	}

	/**
	 * @param string $key
	 * @param string $metaValue
	 * @return static
	 */
	public function withAddedMetaValue($key, $metaValue) {
		$new = clone $this;

		if (!isset($new->meta)) {
			$new->meta = [];
		}

		$new->meta[$key] = $metaValue;
		$new->_touchedFields[self::FIELD_META] = true;

		return $new;
	}

}
