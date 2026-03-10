<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\TestSuite;

use Cake\TestSuite\TestCase;
use CakeDto\TestSuite\DebugMemory;

/**
 * @uses \CakeDto\TestSuite\DebugMemory
 */
class DebugMemoryTest extends TestCase {

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		DebugMemory::clear();
	}

	/**
	 * @return void
	 */
	protected function tearDown(): void {
		DebugMemory::clear();

		parent::tearDown();
	}

	/**
	 * Test getCurrent returns memory usage.
	 *
	 * @return void
	 */
	public function testGetCurrent(): void {
		$memory = DebugMemory::getCurrent();

		$this->assertIsInt($memory);
		$this->assertGreaterThan(0, $memory);
	}

	/**
	 * Test getPeak returns peak memory usage.
	 *
	 * @return void
	 */
	public function testGetPeak(): void {
		$memory = DebugMemory::getPeak();

		$this->assertIsInt($memory);
		$this->assertGreaterThan(0, $memory);
	}

	/**
	 * Test getPeak is greater than or equal to getCurrent.
	 *
	 * @return void
	 */
	public function testGetPeakGreaterThanCurrent(): void {
		$current = DebugMemory::getCurrent();
		$peak = DebugMemory::getPeak();

		$this->assertGreaterThanOrEqual($current, $peak);
	}

	/**
	 * Test record stores memory point.
	 *
	 * @return void
	 */
	public function testRecord(): void {
		$result = DebugMemory::record('test_point');

		$this->assertTrue($result);

		$all = DebugMemory::getAll();
		$this->assertArrayHasKey('test_point', $all);
		$this->assertIsInt($all['test_point']);
	}

	/**
	 * Test record without message uses caller info.
	 *
	 * @return void
	 */
	public function testRecordWithoutMessage(): void {
		$result = DebugMemory::record();

		$this->assertTrue($result);

		$all = DebugMemory::getAll();
		$this->assertNotEmpty($all);
	}

	/**
	 * Test record with duplicate messages.
	 *
	 * @return void
	 */
	public function testRecordDuplicateMessages(): void {
		DebugMemory::record('test');
		DebugMemory::record('test');
		DebugMemory::record('test');

		$all = DebugMemory::getAll();
		$this->assertArrayHasKey('test', $all);
		$this->assertArrayHasKey('test #2', $all);
		$this->assertArrayHasKey('test #3', $all);
	}

	/**
	 * Test getAll returns all points.
	 *
	 * @return void
	 */
	public function testGetAll(): void {
		DebugMemory::record('point1');
		DebugMemory::record('point2');

		$all = DebugMemory::getAll();

		$this->assertArrayHasKey('point1', $all);
		$this->assertArrayHasKey('point2', $all);
	}

	/**
	 * Test getAll with clear.
	 *
	 * @return void
	 */
	public function testGetAllWithClear(): void {
		DebugMemory::record('test');

		$all = DebugMemory::getAll(true);
		$this->assertArrayHasKey('test', $all);

		$afterClear = DebugMemory::getAll();
		$this->assertEmpty($afterClear);
	}

	/**
	 * Test getAll without clear preserves points.
	 *
	 * @return void
	 */
	public function testGetAllWithoutClear(): void {
		DebugMemory::record('test');

		$all = DebugMemory::getAll(false);
		$this->assertArrayHasKey('test', $all);

		$afterNoChange = DebugMemory::getAll();
		$this->assertArrayHasKey('test', $afterNoChange);
	}

	/**
	 * Test clear removes all points.
	 *
	 * @return void
	 */
	public function testClear(): void {
		DebugMemory::record('test');
		DebugMemory::clear();

		$all = DebugMemory::getAll();
		$this->assertEmpty($all);
	}

}
