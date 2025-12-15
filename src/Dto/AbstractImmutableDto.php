<?php

declare(strict_types=1);

namespace CakeDto\Dto;

use PhpCollective\Dto\Dto\AbstractImmutableDto as BaseAbstractImmutableDto;

/**
 * CakePHP-specific AbstractImmutableDto with corrected fast path logic.
 *
 * The fast path optimization is used only in lenient mode (ignoreMissing=true),
 * ensuring strict mode (!ignoreMissing) maintains full type validation.
 */
abstract class AbstractImmutableDto extends BaseAbstractImmutableDto {

	/**
	 * @param array<string, mixed>|null $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 */
	public function __construct(?array $data = null, bool $ignoreMissing = false, ?string $type = null) {
		if ($data) {
			// Use optimized fast path only when ignoreMissing is true (lenient mode)
			// In strict mode (!$ignoreMissing), we need full type validation
			if ($ignoreMissing && $type === null && method_exists($this, 'setFromArrayFast')) {
				$this->setFromArrayFast($data);
			} else {
				$this->setFromArray($data, $ignoreMissing, $type);
			}
		}

		$this->setDefaults();
		$this->validate();
	}

}
