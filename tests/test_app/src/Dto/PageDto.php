<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto;

/**
 * Page DTO
 *
 * @property int $number
 * @property string|null $content
 *
 * @method array{number: int, content: string|null} toArray(?string $type = null, ?array $fields = null, bool $touched = false)
 * @method static static createFromArray(array{number: int, content: string|null} $data, bool $ignoreMissing = false, ?string $type = null)
 */
class PageDto extends AbstractImmutableDto {

	/**
	 * @var string
	 */
	public const FIELD_NUMBER = 'number';
	/**
	 * @var string
	 */
	public const FIELD_CONTENT = 'content';

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
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'number' => [
			'name' => 'number',
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
		'content' => [
			'name' => 'content',
			'type' => 'string',
			'required' => false,
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
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
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
	 * Whether this DTO is immutable.
	 */
	protected const IS_IMMUTABLE = true;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'number' => 'withNumber',
		'content' => 'withContent',
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
		if ($this->number === null) {
			$errors = [];
			if ($this->number === null) {
				$errors[] = 'number';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param int $number
	 *
	 * @return static
	 */
	public function withNumber(int $number) {
		$new = clone $this;
		$new->number = $number;
		$new->_touchedFields[static::FIELD_NUMBER] = true;

		return $new;
	}

	/**
	 * @return int
	 */
	public function getNumber(): int {
		return $this->number;
	}

	/**
	 * @return bool
	 */
	public function hasNumber(): bool {
		return $this->number !== null;
	}

	/**
	 * @param string|null $content
	 *
	 * @return static
	 */
	public function withContent(?string $content = null) {
		$new = clone $this;
		$new->content = $content;
		$new->_touchedFields[static::FIELD_CONTENT] = true;

		return $new;
	}

	/**
	 * @param string $content
	 *
	 * @return static
	 */
	public function withContentOrFail(string $content) {
		$new = clone $this;
		$new->content = $content;
		$new->_touchedFields[static::FIELD_CONTENT] = true;

		return $new;
	}

	/**
	 * @return string|null
	 */
	public function getContent(): ?string {
		return $this->content;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getContentOrFail(): string {
		if ($this->content === null) {
			throw new \RuntimeException('Value not set for field `content` (expected to be not null)');
		}

		return $this->content;
	}

	/**
	 * @return bool
	 */
	public function hasContent(): bool {
		return $this->content !== null;
	}


	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{number: int, content: string|null}
	 */
	#[\Override]
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @phpstan-ignore return.type (parent returns array, we provide shape for IDE) */
		return parent::toArray($type, $fields, $touched);
	}

	/**
	 * @param array{number: int, content: string|null} $data
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
