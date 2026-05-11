<?php

namespace CakeDto\Test\TestCase\Mapper;

use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakeDto\Mapper\DtoMapper;
use CakeDto\Mapper\DtoPagination;
use TestApp\Dto\ArticleDto;
use TestApp\Model\Entity\Article;
use TestApp\Model\Entity\Author;
use TestApp\Model\Entity\Tag;

class DtoMapperTest extends TestCase {

	/**
	 * @return void
	 */
	public function testFromEntity(): void {
		$article = $this->createArticleEntity();

		$dto = DtoMapper::fromEntity(
			entity: $article,
			dtoClass: ArticleDto::class,
			ignoreMissing: true,
		);

		$this->assertInstanceOf(ArticleDto::class, $dto);
		$this->assertSame('My title', $dto->getTitle());
		$this->assertSame('me', $dto->getAuthor()->getName());
		$this->assertSame('Awesome', $dto->getTags()[0]->getName());
	}

	/**
	 * @return void
	 */
	public function testFromIterable(): void {
		$first = $this->createArticleEntity();
		$second = $this->createArticleEntity();
		$second->id = 3;
		$second->title = 'Other title';

		$dtos = DtoMapper::fromIterable(
			items: [$first, $second],
			dtoClass: ArticleDto::class,
			ignoreMissing: true,
		);

		$this->assertCount(2, $dtos);
		$this->assertSame(3, $dtos[1]->getId());
		$this->assertSame('Other title', $dtos[1]->getTitle());
	}

	/**
	 * @return void
	 */
	public function testFromPaginated(): void {
		$article = $this->createArticleEntity();

		$paging = [
			'Articles' => [
				'page' => 2,
				'current' => 1,
				'count' => 5,
				'perPage' => 1,
				'pageCount' => 5,
			],
		];

		$pagination = DtoMapper::fromPaginated(
			items: [$article],
			paging: $paging,
			alias: 'Articles',
			dtoClass: ArticleDto::class,
			ignoreMissing: true,
		);

		$this->assertInstanceOf(DtoPagination::class, $pagination);
		$this->assertSame(
			[
				'page' => 2,
				'perPage' => 1,
				'count' => 5,
				'pageCount' => 5,
				'current' => 1,
				'hasNext' => true,
				'hasPrev' => true,
			],
			$pagination->getMeta(),
		);

		$data = $pagination->toArray();
		$this->assertSame('My title', $data['data'][0]['title']);
		$this->assertSame(2, $data['meta']['page']);
	}

	/**
	 * Regression: object-typed entity properties (DateTime, Decimal, custom
	 * value objects) used to be serialized to scalars via `$entity->toArray()`
	 * and re-coerced by the DTO constructor. The mapper now extracts the raw
	 * properties so the DateTime instance passes straight through.
	 *
	 * @return void
	 */
	public function testFromEntityPreservesObjectTypedProperty(): void {
		// The DTO's `created` is typed `\Cake\I18n\Date`. With the pre-fix
		// `toArray()` path this exact instance got serialized to a string
		// and the DTO constructor had to re-parse it; with the new
		// `extract()` path the very same object passes straight through.
		$created = Date::parseDate('2026-05-11', 'yyyy-MM-dd');
		$this->assertNotNull($created);
		$article = new Article([
			'id' => 7,
			'author_id' => 1,
			'author' => new Author(['id' => 1, 'name' => 'me']),
			'title' => 'Title',
			'created' => $created,
		]);

		$dto = DtoMapper::fromEntity(
			entity: $article,
			dtoClass: ArticleDto::class,
			ignoreMissing: true,
		);

		$result = $dto->getCreated();
		$this->assertInstanceOf(Date::class, $result);
		// Same instance, not a re-parsed copy — proves the value object
		// survived the mapper without an intermediate string round-trip.
		$this->assertSame($created, $result);
	}

	/**
	 * @return \TestApp\Model\Entity\Article
	 */
	protected function createArticleEntity(): Article {
		return new Article([
			'id' => 2,
			'author_id' => 1,
			'author' => new Author(['id' => 1, 'name' => 'me']),
			'title' => 'My title',
			'created' => new DateTime(),
			'tags' => [
				new Tag(['id' => 3, 'name' => 'Awesome']),
			],
			'meta' => [
				'source' => 'test',
			],
		]);
	}

}
