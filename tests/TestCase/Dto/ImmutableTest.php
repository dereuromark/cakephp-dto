<?php

namespace CakeDto\Test\TestCase\Dto;

use CakeDto\Dto\AbstractImmutableDto;
use Cake\Collection\Collection;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use TestApp\Dto\ArticleDto;
use TestApp\Dto\BookDto;
use TestApp\Dto\PageDto;

class ImmutableTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testBasic() {
		$array = [
			'id' => 2,
			'author' => [
				'id' => 1,
				'name' => 'me'
			],
			'title' => 'My title',
			'created' => new FrozenTime(time() - DAY),
		];

		$articleDto = new ArticleDto($array);
		$this->assertInstanceOf(AbstractImmutableDto::class, $articleDto);

		// A trivial example
		$modifiedArticleDto = $articleDto->withTitle('My new title');
		$this->assertSame('My new title', $modifiedArticleDto->getTitle());
		$this->assertSame('My title', $articleDto->getTitle());

		// A reason why we want to use immutable datetime objects (FrozenTime):
		$created = $articleDto->getCreated();
		$isToday = $created->addDay()->isToday();
		// A mutable datetime inside $articleDto->getCreated() would now accidentally be modified
		$this->assertTrue($isToday);

		// But luckily we don't have this side effect with our immutable one.
		$this->assertSame($created, $articleDto->getCreated());
	}

	/**
	 * @return void
	 */
	public function testCakeCollection() {
		$bookDto = new BookDto();
		$bookDto = $bookDto->withAddedPage(new PageDto(['number' => 1]));

		$array = $bookDto->toArray();
		$this->assertInstanceOf(Collection::class, $array['pages']);
		$this->assertSame(1, $array['pages']->count());

		$pages = $bookDto->getPages()->toArray();
		$this->assertInstanceOf(PageDto::class, $pages[0]);
		$this->assertSame(1, $bookDto->getPages()->count());

		$array = [
			'pages' => [
				[
					'number' => 1,
				],
				[
					'number' => 2,
				],
			]
		];
		$bookDto = BookDto::createFromArray($array);

		$this->assertInstanceOf(Collection::class, $bookDto->getPages());
		$this->assertSame(2, $bookDto->getPages()->count());

		$this->assertSame(1, $bookDto->getPages()->first()->getNumber());
		$this->assertSame(2, $bookDto->getPages()->last()->getNumber());

		// It seems we lose an item if we keep preserveKeys true...
		$result = $bookDto->getPages()->toArray(false);
		$this->assertCount(2, $result);

		$pageArray = $result[0]->touchedToArray();
		$expected = [
			'number' => 1,
		];
		$this->assertSame($expected, $pageArray);

		$pageArray = $result[1]->touchedToArray();
		$expected = [
			'number' => 2,
		];
		$this->assertSame($expected, $pageArray);
	}

	/**
	 * @return void
	 */
	public function testWithGetHas() {
		$bookDto = new BookDto();

		$this->assertFalse($bookDto->has($bookDto::FIELD_PAGES));

		$pages = new Collection([new PageDto(['number' => 1])]);
		$bookDto = $bookDto->with('pages', $pages);

		$this->assertSame(1, $bookDto->get($bookDto::FIELD_PAGES)->count());

		$this->assertTrue($bookDto->has($bookDto::FIELD_PAGES));
	}

	/**
	 * @return void
	 */
	public function testPropertyAccessFails() {
		$this->skipIf(version_compare(PHP_VERSION, '7.0') < 0, 'Fatal error before PHP 7.');

		$bookDto = new BookDto();
		$pages = $bookDto->pages;
		$this->assertSame(0, $pages->count());

		$this->expectException('Error');

		$bookDto->pages = new Collection([]);
	}

}
