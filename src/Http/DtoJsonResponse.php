<?php

declare(strict_types=1);

namespace CakeDto\Http;

use Cake\Http\Response;
use CakeDto\Mapper\DtoPagination;
use PhpCollective\Dto\Dto\Dto;

class DtoJsonResponse {

	/**
	 * @param \PhpCollective\Dto\Dto\Dto $dto
	 * @param int $status
	 * @param array<string, string|array<string>> $headers
	 *
	 * @throws \JsonException
	 *
	 * @return \Cake\Http\Response
	 */
	public static function fromDto(Dto $dto, int $status = 200, array $headers = []): Response {
		return static::build($dto->toArray(), $status, $headers);
	}

	/**
	 * @param iterable<\PhpCollective\Dto\Dto\Dto> $items
	 * @param int $status
	 * @param array<string, string|array<string>> $headers
	 *
	 * @throws \JsonException
	 *
	 * @return \Cake\Http\Response
	 */
	public static function fromCollection(iterable $items, int $status = 200, array $headers = []): Response {
		$data = [];
		foreach ($items as $item) {
			$data[] = $item->toArray();
		}

		return static::build($data, $status, $headers);
	}

	/**
	 * @param \CakeDto\Mapper\DtoPagination $pagination
	 * @param int $status
	 * @param array<string, string|array<string>> $headers
	 *
	 * @throws \JsonException
	 *
	 * @return \Cake\Http\Response
	 */
	public static function fromPagination(DtoPagination $pagination, int $status = 200, array $headers = []): Response {
		return static::build($pagination->toArray(), $status, $headers);
	}

	/**
	 * @param array<string, mixed>|array<int, array<string, mixed>> $data
	 * @param int $status
	 * @param array<string, string|array<string>> $headers
	 *
	 * @throws \JsonException
	 *
	 * @return \Cake\Http\Response
	 */
	protected static function build(array $data, int $status, array $headers): Response {
		$response = new Response();
		$response = $response
			->withStatus($status)
			->withType('application/json')
			->withStringBody(json_encode($data, JSON_THROW_ON_ERROR));

		foreach ($headers as $name => $value) {
			$response = $response->withHeader($name, $value);
		}

		return $response;
	}

}
