<?php

declare(strict_types=1);

namespace CakeDto\Mapper;

use PhpCollective\Dto\Dto\Dto;

class DtoPagination {

	/**
	 * @var array<int, \PhpCollective\Dto\Dto\Dto>
	 */
	protected array $items;

	/**
	 * @var array{page: int, perPage: int, count: int, pageCount: int, current: int, hasNext: bool, hasPrev: bool}
	 */
	protected array $meta;

	/**
	 * @param array<int, \PhpCollective\Dto\Dto\Dto> $items
	 * @param array{page: int, perPage: int, count: int, pageCount: int, current: int, hasNext: bool, hasPrev: bool} $meta
	 */
	public function __construct(array $items, array $meta) {
		$this->items = $items;
		$this->meta = $meta;
	}

	/**
	 * @return array<int, \PhpCollective\Dto\Dto\Dto>
	 */
	public function getItems(): array {
		return $this->items;
	}

	/**
	 * @return array{page: int, perPage: int, count: int, pageCount: int, current: int, hasNext: bool, hasPrev: bool}
	 */
	public function getMeta(): array {
		return $this->meta;
	}

	/**
	 * @param string|null $type
	 *
	 * @return array{data: array<int, array<string, mixed>>, meta: array{page: int, perPage: int, count: int, pageCount: int, current: int, hasNext: bool, hasPrev: bool}}
	 */
	public function toArray(?string $type = null): array {
		$data = array_map(
			static fn (Dto $dto): array => $dto->toArray($type),
			$this->items,
		);

		return [
			'data' => $data,
			'meta' => $this->meta,
		];
	}

}
