<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Mapper;

use Cake\TestSuite\TestCase;
use CakeDto\Mapper\DtoPagination;
use TestApp\Dto\CarDto;

/**
 * @uses \CakeDto\Mapper\DtoPagination
 */
class DtoPaginationTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$items = [];
		$meta = [
			'page' => 1,
			'perPage' => 10,
			'count' => 100,
			'pageCount' => 10,
			'current' => 10,
			'hasNext' => true,
			'hasPrev' => false,
		];

		$pagination = new DtoPagination($items, $meta);

		$this->assertInstanceOf(DtoPagination::class, $pagination);
	}

	/**
	 * @return void
	 */
	public function testGetItems(): void {
		$item1 = CarDto::create(['isNew' => true]);
		$item2 = CarDto::create(['isNew' => false]);
		$items = [$item1, $item2];
		$meta = [
			'page' => 1,
			'perPage' => 10,
			'count' => 2,
			'pageCount' => 1,
			'current' => 2,
			'hasNext' => false,
			'hasPrev' => false,
		];

		$pagination = new DtoPagination($items, $meta);

		$this->assertSame($items, $pagination->getItems());
		$this->assertCount(2, $pagination->getItems());
	}

	/**
	 * @return void
	 */
	public function testGetMeta(): void {
		$items = [];
		$meta = [
			'page' => 2,
			'perPage' => 20,
			'count' => 50,
			'pageCount' => 3,
			'current' => 20,
			'hasNext' => true,
			'hasPrev' => true,
		];

		$pagination = new DtoPagination($items, $meta);

		$this->assertSame($meta, $pagination->getMeta());
		$this->assertSame(2, $pagination->getMeta()['page']);
		$this->assertSame(20, $pagination->getMeta()['perPage']);
	}

	/**
	 * @return void
	 */
	public function testToArray(): void {
		$item1 = CarDto::create(['isNew' => true]);
		$item2 = CarDto::create(['isNew' => false]);
		$items = [$item1, $item2];
		$meta = [
			'page' => 1,
			'perPage' => 10,
			'count' => 2,
			'pageCount' => 1,
			'current' => 2,
			'hasNext' => false,
			'hasPrev' => false,
		];

		$pagination = new DtoPagination($items, $meta);
		$result = $pagination->toArray();

		$this->assertArrayHasKey('data', $result);
		$this->assertArrayHasKey('meta', $result);
		$this->assertCount(2, $result['data']);
		$this->assertSame($meta, $result['meta']);
	}

	/**
	 * @return void
	 */
	public function testToArrayWithEmptyItems(): void {
		$items = [];
		$meta = [
			'page' => 1,
			'perPage' => 10,
			'count' => 0,
			'pageCount' => 0,
			'current' => 0,
			'hasNext' => false,
			'hasPrev' => false,
		];

		$pagination = new DtoPagination($items, $meta);
		$result = $pagination->toArray();

		$this->assertEmpty($result['data']);
		$this->assertSame($meta, $result['meta']);
	}

	/**
	 * @return void
	 */
	public function testToArrayWithNullType(): void {
		$item = CarDto::create(['isNew' => true]);
		$items = [$item];
		$meta = [
			'page' => 1,
			'perPage' => 10,
			'count' => 1,
			'pageCount' => 1,
			'current' => 1,
			'hasNext' => false,
			'hasPrev' => false,
		];

		$pagination = new DtoPagination($items, $meta);
		$result = $pagination->toArray(null);

		$this->assertArrayHasKey('data', $result);
		$this->assertCount(1, $result['data']);
		$this->assertArrayHasKey('isNew', $result['data'][0]);
	}

}
