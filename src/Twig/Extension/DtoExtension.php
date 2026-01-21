<?php
declare(strict_types=1);

namespace CakeDto\Twig\Extension;

use PhpCollective\Dto\Collection\CollectionAdapterInterface;
use PhpCollective\Dto\Collection\CollectionAdapterRegistry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension for DTO-specific filters and functions.
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
	 * @return array<\Twig\TwigFunction>
	 */
	public function getFunctions(): array {
		return [
			new TwigFunction('getCollectionAdapter', [$this, 'getCollectionAdapter']),
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

	/**
	 * Get the collection adapter for a given collection type.
	 *
	 * @param string $collectionType The collection class name
	 * @return \PhpCollective\Dto\Collection\CollectionAdapterInterface
	 */
	public function getCollectionAdapter(string $collectionType): CollectionAdapterInterface {
		return CollectionAdapterRegistry::getOrDefault($collectionType);
	}

}
