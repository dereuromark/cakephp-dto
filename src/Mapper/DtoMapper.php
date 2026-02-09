<?php

declare(strict_types=1);

namespace CakeDto\Mapper;

use Cake\Datasource\EntityInterface;
use InvalidArgumentException;
use PhpCollective\Dto\Dto\Dto;

class DtoMapper {

	/**
	 * @param \Cake\Datasource\EntityInterface $entity
	 * @param class-string<\PhpCollective\Dto\Dto\Dto> $dtoClass
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return \PhpCollective\Dto\Dto\Dto
	 */
	public static function fromEntity(EntityInterface $entity, string $dtoClass, bool $ignoreMissing = false, ?string $type = null): Dto {
		static::assertDtoClass($dtoClass);

		return $dtoClass::createFromArray($entity->toArray(), $ignoreMissing, $type);
	}

	/**
	 * @param iterable<\Cake\Datasource\EntityInterface|array|Dto> $items
	 * @param class-string<\PhpCollective\Dto\Dto\Dto> $dtoClass
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return array<int, \PhpCollective\Dto\Dto\Dto>
	 */
	public static function fromIterable(iterable $items, string $dtoClass, bool $ignoreMissing = false, ?string $type = null): array {
		static::assertDtoClass($dtoClass);
		$result = [];

		foreach ($items as $item) {
			$result[] = static::mapItem($item, $dtoClass, $ignoreMissing, $type);
		}

		return $result;
	}

	/**
	 * @param iterable<\Cake\Datasource\EntityInterface|array|Dto> $items
	 * @param array<string, mixed> $paging
	 * @param non-empty-string|string $alias
	 * @param class-string<\PhpCollective\Dto\Dto\Dto> $dtoClass
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return \CakeDto\Mapper\DtoPagination
	 */
	public static function fromPaginated(
		iterable $items,
		array $paging,
		string $alias,
		string $dtoClass,
		bool $ignoreMissing = false,
		?string $type = null,
	): DtoPagination {
		$meta = static::extractPaging($paging, $alias);
		$mapped = static::fromIterable($items, $dtoClass, $ignoreMissing, $type);

		return new DtoPagination($mapped, $meta);
	}

	/**
	 * @param \Cake\Datasource\EntityInterface|\PhpCollective\Dto\Dto\Dto|array $item
	 * @param class-string<\PhpCollective\Dto\Dto\Dto> $dtoClass
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return \PhpCollective\Dto\Dto\Dto
	 */
	protected static function mapItem(
		EntityInterface|array|Dto $item,
		string $dtoClass,
		bool $ignoreMissing,
		?string $type,
	): Dto {
		if ($item instanceof Dto) {
			if (!$item instanceof $dtoClass) {
				throw new InvalidArgumentException(sprintf(
					'Expected DTO instance of `%s`, got `%s`.',
					$dtoClass,
					$item::class,
				));
			}

			return $item;
		}

		if ($item instanceof EntityInterface) {
			$data = $item->toArray();
		} elseif (is_array($item)) {
			$data = $item;
		} else {
			throw new InvalidArgumentException(sprintf(
				'Invalid DTO item type `%s`.',
				get_debug_type($item),
			));
		}

		return $dtoClass::createFromArray($data, $ignoreMissing, $type);
	}

	/**
	 * @param array<string, mixed> $paging
	 * @param non-empty-string|string $alias
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return array{page: int, perPage: int, count: int, pageCount: int, current: int, hasNext: bool, hasPrev: bool}
	 */
	protected static function extractPaging(array $paging, string $alias): array {
		if (!array_key_exists($alias, $paging)) {
			throw new InvalidArgumentException(sprintf('Missing paging data for alias `%s`.', $alias));
		}

		$params = $paging[$alias];
		if (!is_array($params)) {
			throw new InvalidArgumentException(sprintf('Invalid paging data for alias `%s`.', $alias));
		}

		$required = ['page', 'perPage', 'count', 'pageCount', 'current'];
		foreach ($required as $key) {
			if (!array_key_exists($key, $params)) {
				throw new InvalidArgumentException(sprintf(
					'Missing `%s` paging key for alias `%s`.',
					$key,
					$alias,
				));
			}
		}

		$page = (int)$params['page'];
		$perPage = (int)$params['perPage'];
		$count = (int)$params['count'];
		$pageCount = (int)$params['pageCount'];
		$current = (int)$params['current'];

		return [
			'page' => $page,
			'perPage' => $perPage,
			'count' => $count,
			'pageCount' => $pageCount,
			'current' => $current,
			'hasNext' => $page < $pageCount,
			'hasPrev' => $page > 1,
		];
	}

	/**
	 * @param class-string $dtoClass
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	protected static function assertDtoClass(string $dtoClass): void {
		if (!class_exists($dtoClass) || !is_subclass_of($dtoClass, Dto::class)) {
			throw new InvalidArgumentException(sprintf(
				'DTO class `%s` must extend %s.',
				$dtoClass,
				Dto::class,
			));
		}
	}

}
