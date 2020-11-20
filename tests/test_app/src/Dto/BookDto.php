<?php
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

/**
 * Book DTO
 *
 * @property \TestApp\Dto\PageDto[]|\Cake\Collection\Collection $pages
 */
class BookDto extends \CakeDto\Dto\AbstractImmutableDto {

	public const FIELD_PAGES = 'pages';

	/**
	 * @var \TestApp\Dto\PageDto[]|\Cake\Collection\Collection
	 */
	protected $pages;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array
	 */
	protected $_metadata = [
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
			'singularType' => '\TestApp\Dto\PageDto',
			'singularNullable' => false,
			'singularTypeHint' => '\TestApp\Dto\PageDto',
		],
	];

	/**
	* @var array
	*/
	protected $_keyMap = [
		'underscored' => [
			'pages' => 'pages',
		],
		'dashed' => [
			'pages' => 'pages',
		],
	];

	/**
	 * @param \TestApp\Dto\PageDto[]|\Cake\Collection\Collection $pages
	 *
	 * @return static
	 */
	public function withPages(\Cake\Collection\Collection $pages) {
		$new = clone $this;
		$new->pages = $pages;
		$new->_touchedFields[self::FIELD_PAGES] = true;

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

		if (!isset($new->pages)) {
			$new->pages = new \Cake\Collection\Collection([]);
		}

		$new->pages = $new->pages->appendItem($page);
		$new->_touchedFields[self::FIELD_PAGES] = true;

		return $new;
	}

}
