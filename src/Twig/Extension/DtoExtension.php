<?php
declare(strict_types=1);

namespace CakeDto\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extension for DTO-specific filters.
 */
class DtoExtension extends AbstractExtension {

	/**
	 * @return array<\Twig\TwigFilter>
	 */
	public function getFilters(): array {
		return [
			new TwigFilter('stripLeadingUnderscore', [$this, 'stripLeadingUnderscore']),
		];
	}

	/**
	 * Strip leading underscore from a string.
	 *
	 * Used for underscore-prefixed field names (e.g., _joinData, _matchingData)
	 * to generate proper camelCase method names and constant names.
	 *
	 * @param string $value
	 * @return string
	 */
	public function stripLeadingUnderscore(string $value): string {
		if (str_starts_with($value, '_')) {
			return substr($value, 1);
		}

		return $value;
	}

}
