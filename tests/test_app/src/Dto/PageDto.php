<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Page DTO
 */
class PageDto extends \CakeDto\Dto\AbstractImmutableDto {

	const FIELD_NUMBER = 'number';
	const FIELD_CONTENT = 'content';

	/**
	 * @var int
	 */
	protected $number;

	/**
	 * @var string|null
	 */
	protected $content;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
		'number' => [
			'name' => 'number',
			'type' => 'int',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
		'content' => [
			'name' => 'content',
			'type' => 'string',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'serializable' => false,
			'toArray' => false,
		],
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
		'underscored' => [
			'number' => 'number',
			'content' => 'content',
		],
		'dashed' => [
			'number' => 'number',
			'content' => 'content',
		],
	];

	/**
	 * @param int $number
	 *
	 * @return static
	 */
	public function withNumber($number) {
		$new = clone $this;
		$new->number = $number;
		$new->_touchedFields[self::FIELD_NUMBER] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @return bool
	 */
	public function hasNumber() {
		return $this->number !== null;
	}

	/**
	 * @param string|null $content
	 *
	 * @return static
	 */
	public function withContent($content) {
		$new = clone $this;
		$new->content = $content;
		$new->_touchedFields[self::FIELD_CONTENT] = true;

		return $new;
	}

	/**
	 * @return string|null
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getContentOrFail() {
		if (!isset($this->content)) {
			throw new \RuntimeException('Value not set for field `content` (expected to be not null)');
		}

		return $this->content;
	}

	/**
	 * @return bool
	 */
	public function hasContent() {
		return $this->content !== null;
	}

}
