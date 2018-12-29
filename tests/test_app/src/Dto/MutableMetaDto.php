<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * MutableMeta DTO
 */
class MutableMetaDto extends \CakeDto\Dto\AbstractDto {

	const FIELD_TITLE = 'title';
	const FIELD_META = 'meta';

	/**
	 * @var string
	 */
	protected $title;

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
		'title' => [
			'name' => 'title',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serializable' => false,
			'toArray' => false,
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
			'title' => 'title',
			'meta' => 'meta',
		],
		'dashed' => [
			'title' => 'title',
			'meta' => 'meta',
		],
	];

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle($title) {
		$this->title = $title;
		$this->_touchedFields[self::FIELD_TITLE] = true;

		return $this;
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
	 * @param string[] $meta
	 *
	 * @return $this
	 */
	public function setMeta(array $meta) {
		$this->meta = $meta;
		$this->_touchedFields[self::FIELD_META] = true;

		return $this;
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
	 * @return $this
	 */
	public function addMetaValue($key, $metaValue) {
		if (!isset($this->meta)) {
			$this->meta = [];
		}

		$this->meta[$key] = $metaValue;
		$this->_touchedFields[self::FIELD_META] = true;

		return $this;
	}

}
