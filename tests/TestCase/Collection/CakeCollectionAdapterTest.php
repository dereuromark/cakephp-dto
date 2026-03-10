<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Collection;

use Cake\TestSuite\TestCase;
use CakeDto\Collection\CakeCollectionAdapter;

/**
 * @uses \CakeDto\Collection\CakeCollectionAdapter
 */
class CakeCollectionAdapterTest extends TestCase {

	/**
	 * @var \CakeDto\Collection\CakeCollectionAdapter
	 */
	protected CakeCollectionAdapter $adapter;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->adapter = new CakeCollectionAdapter();
	}

	/**
	 * Test getCollectionClass returns Cake Collection class.
	 *
	 * @return void
	 */
	public function testGetCollectionClass(): void {
		$result = $this->adapter->getCollectionClass();

		$this->assertSame('\\Cake\\Collection\\Collection', $result);
	}

	/**
	 * Test isImmutable returns true for Cake Collection.
	 *
	 * @return void
	 */
	public function testIsImmutable(): void {
		$result = $this->adapter->isImmutable();

		$this->assertTrue($result);
	}

	/**
	 * Test getAppendMethod returns appendItem.
	 *
	 * @return void
	 */
	public function testGetAppendMethod(): void {
		$result = $this->adapter->getAppendMethod();

		$this->assertSame('appendItem', $result);
	}

	/**
	 * Test getCreateEmptyCode returns correct code.
	 *
	 * @return void
	 */
	public function testGetCreateEmptyCode(): void {
		$result = $this->adapter->getCreateEmptyCode('\\Cake\\Collection\\Collection');

		$this->assertSame('new \\Cake\\Collection\\Collection([])', $result);
	}

	/**
	 * Test getCreateEmptyCode with generic type hint.
	 *
	 * @return void
	 */
	public function testGetCreateEmptyCodeGeneric(): void {
		$result = $this->adapter->getCreateEmptyCode('Collection');

		$this->assertSame('new Collection([])', $result);
	}

	/**
	 * Test getAppendCode returns correct assignment code.
	 *
	 * @return void
	 */
	public function testGetAppendCode(): void {
		$result = $this->adapter->getAppendCode('$collection', '$item');

		$this->assertSame('$collection = $collection->appendItem($item);', $result);
	}

	/**
	 * Test getAppendCode with different variable names.
	 *
	 * @return void
	 */
	public function testGetAppendCodeDifferentVars(): void {
		$result = $this->adapter->getAppendCode('$items', '$newItem');

		$this->assertSame('$items = $items->appendItem($newItem);', $result);
	}

}
