<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use CakeDto\Dto\AbstractDto;

/**
 * MutableMeta DTO
 *
 * @property string $title
 * @property (string|null)[] $meta
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
	 * @var (string|null)[]
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
	 * @param (string|null)[] $meta
	 *
	 * @return $this
	 */
	public function setMeta(array $meta) {
		$this->meta = $meta;
		$this->_touchedFields[static::FIELD_META] = true;

		return $this;
	}

	/**
	 * @return (string|null)[]
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
	 * @param string|int $key
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

}
