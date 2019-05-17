<?php

namespace Dto\Test\TestCase\Dto;

use Cake\I18n\FrozenTime;
use PHPUnit\Framework\TestCase;
use TestApp\Dto\ArticleDto;
use TestApp\Model\Entity\Article;
use TestApp\Model\Entity\Author;
use TestApp\Model\Entity\Tag;

class OrmTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testEmptyCollection() {
		$articleEntity = new Article();
		$articleEntity->id = 2; // We simulate a persisted entity and its persisted relations
		$articleEntity->author = new Author(['id' => 1, 'name' => 'me']);
		$articleEntity->title = 'My title';
		$articleEntity->created = new FrozenTime();

		$articleDto = new ArticleDto($articleEntity->toArray());

		$array = $articleDto->toArray();
		$this->assertSame([], $array['tags']);
		$this->assertSame([], $array['meta']);
	}

	/**
	 * @return void
	 */
	public function testMapping() {
		$articleEntity = new Article();
		$articleEntity->id = 2; // We simulate a persisted entity and its persisted relations
		$articleEntity->author_id = 1;
		$articleEntity->author = new Author(['id' => 1, 'name' => 'me']);
		$articleEntity->title = 'My title';
		$articleEntity->created = new FrozenTime();
		$articleEntity->tags = [
			new Tag(['id' => 3, 'name' => 'Awesome']),
			new Tag(['id' => 4, 'name' => 'Shiny']),
		];

		$array = $articleEntity->toArray();
		$this->assertInstanceOf(FrozenTime::class, $array['created']);
		unset($array['created']); // We can't assertSame() on objects
		$expected = [
			'id' => 2,
			'author_id' => 1,
			'author' => [
				'id' => 1,
				'name' => 'me'
			],
			'title' => 'My title',
			'tags' => [
				[
					'id' => 3,
					'name' => 'Awesome',
				],
				[
					'id' => 4,
					'name' => 'Shiny',
				]
			]
		];
		$this->assertSame($expected, $array);

		$articleDto = new ArticleDto($articleEntity->toArray(), true);

		unset($expected['author_id']); // This will get lost on purpose

		$result = $articleDto->touchedToArray();
		$this->assertInstanceOf(FrozenTime::class, $result['created']);
		unset($result['created']); // We can't assertSame() on objects

		// Exactly the same :)
		$this->assertSame($expected, $result);
	}

}
