<?php

declare(strict_types=1);

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * Test DTO with no validation rules.
 */
class EmptyValidatedDto extends AbstractDto {

	/**
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [];

	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function validationRules(): array {
		return [];
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		return [];
	}

	/**
	 * @param array<string, mixed> $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return new static($data, $ignoreMissing, $type);
	}

}
