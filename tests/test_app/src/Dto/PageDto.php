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
	protected ?string $content = null;

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
			'transformFrom' => null,
			'transformTo' => null,
			'minLength' => null,
			'maxLength' => null,
			'min' => null,
			'max' => null,
			'pattern' => null,
			'lazy' => false,
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
			'transformFrom' => null,
			'transformTo' => null,
			'minLength' => null,
			'maxLength' => null,
			'min' => null,
			'max' => null,
			'pattern' => null,
			'lazy' => false,
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
		'number' => 'withNumber',
		'content' => 'withContent',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['number'])) {
			/** @var int $value */
			$value = $data['number'];
			$this->number = $value;
			$this->_touchedFields['number'] = true;
		}
		if (isset($data['content'])) {
			/** @var string|null $value */
			$value = $data['content'];
			$this->content = $value;
			$this->_touchedFields['content'] = true;
		}
	}

	/**
	 * Optimized toArray for default type without dynamic dispatch.
	 *
	 * @return array<string, mixed>
	 */
	protected function toArrayFast(): array {
		return [
			'number' => $this->number,
			'content' => $this->content,
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
	public function withNumber(int $number): static {
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
	public function withContent(?string $content = null): static {
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
	public function withContentOrFail(string $content): static {
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
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{number: int, content: string|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{number: int, content: string|null} $data
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
