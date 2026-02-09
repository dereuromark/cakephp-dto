<?php

namespace CakeDto\Test\TestCase\Http;

use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakeDto\Http\DtoJsonResponse;
use CakeDto\Mapper\DtoPagination;
use TestApp\Dto\ArticleDto;

class DtoJsonResponseTest extends TestCase {

	/**
	 * @return void
	 */
	public function testFromDto(): void {
		$dto = $this->createArticleDto();

		$response = DtoJsonResponse::fromDto(
			dto: $dto,
			status: 201,
			headers: ['X-Test' => 'yes'],
		);

		$this->assertSame(201, $response->getStatusCode());
		$this->assertSame('yes', $response->getHeaderLine('X-Test'));
		$this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

		$data = $this->decodeJson((string)$response->getBody());
		$this->assertSame('My title', $data['title']);
	}

	/**
	 * @return void
	 */
	public function testFromCollection(): void {
		$first = $this->createArticleDto();
		$second = $this->createArticleDto();

		$response = DtoJsonResponse::fromCollection([$first, $second]);

		$data = $this->decodeJson((string)$response->getBody());
		$this->assertCount(2, $data);
		$this->assertSame('My title', $data[0]['title']);
	}

	/**
	 * @return void
	 */
	public function testFromPagination(): void {
		$dto = $this->createArticleDto();
		$pagination = new DtoPagination(
			[$dto],
			[
				'page' => 1,
				'perPage' => 1,
				'count' => 1,
				'pageCount' => 1,
				'current' => 1,
				'hasNext' => false,
				'hasPrev' => false,
			],
		);

		$response = DtoJsonResponse::fromPagination($pagination);

		$data = $this->decodeJson((string)$response->getBody());
		$this->assertSame(1, $data['meta']['count']);
		$this->assertSame('My title', $data['data'][0]['title']);
	}

	/**
	 * @return \TestApp\Dto\ArticleDto
	 */
	protected function createArticleDto(): ArticleDto {
		return ArticleDto::createFromArray([
			'id' => 2,
			'author' => [
				'id' => 1,
				'name' => 'me',
				'email' => null,
			],
			'title' => 'My title',
			'created' => new DateTime(),
			'tags' => [],
			'meta' => [
				'source' => 'test',
			],
		]);
	}

	/**
	 * @param string $payload
	 *
	 * @throws \JsonException
	 *
	 * @return array<string, mixed>|array<int, array<string, mixed>>
	 */
	protected function decodeJson(string $payload): array {
		/** @var array<string, mixed>|array<int, array<string, mixed>> $data */
		$data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

		return $data;
	}

}
