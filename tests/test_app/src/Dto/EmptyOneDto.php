<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * EmptyOne DTO
 *
 *
 * @method array{} toArray(?string $type = null, ?array $fields = null, bool $touched = false)
 * @method static static createFromArray(array{} $data, bool $ignoreMissing = false, ?string $type = null)
 */
class EmptyOneDto extends AbstractDto {


	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
	];

	/**
	* @var array<string, array<string, string>>
	*/
	protected array $_keyMap = [
		'underscored' => [
		],
		'dashed' => [
		],
	];

	/**
	 * Whether this DTO is immutable.
	 */
	protected const IS_IMMUTABLE = false;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
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
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{}
	 */
	#[\Override]
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @phpstan-ignore return.type (parent returns array, we provide shape for IDE) */
		return parent::toArray($type, $fields, $touched);
	}

	/**
	 * @param array{} $data
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
