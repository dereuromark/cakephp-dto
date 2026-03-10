<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\TestSuite;

use Cake\TestSuite\TestCase;
use CakeDto\TestSuite\DebugTimer;

/**
 * @uses \CakeDto\TestSuite\DebugTimer
 */
class DebugTimerTest extends TestCase {

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		DebugTimer::clear();
	}

	/**
	 * @return void
	 */
	protected function tearDown(): void {
		DebugTimer::clear();

		parent::tearDown();
	}

	/**
	 * Test start returns true.
	 *
	 * @return void
	 */
	public function testStart(): void {
		$result = DebugTimer::start('test');

		$this->assertTrue($result);
	}

	/**
	 * Test start with message.
	 *
	 * @return void
	 */
	public function testStartWithMessage(): void {
		$result = DebugTimer::start('test', 'Test message');

		$this->assertTrue($result);
	}

	/**
	 * Test start without name uses caller info.
	 *
	 * @return void
	 */
	public function testStartWithoutName(): void {
		$result = DebugTimer::start();

		$this->assertTrue($result);
	}

	/**
	 * Test start with duplicate names.
	 *
	 * @return void
	 */
	public function testStartDuplicateNames(): void {
		DebugTimer::start('test');
		DebugTimer::stop('test');
		DebugTimer::start('test');
		DebugTimer::stop('test');

		$all = DebugTimer::getAll();
		$this->assertArrayHasKey('test', $all);
		$this->assertArrayHasKey('test #2', $all);
	}

	/**
	 * Test stop returns true for started timer.
	 *
	 * @return void
	 */
	public function testStop(): void {
		DebugTimer::start('test');
		$result = DebugTimer::stop('test');

		$this->assertTrue($result);
	}

	/**
	 * Test stop returns false for non-existent timer.
	 *
	 * @return void
	 */
	public function testStopNonExistent(): void {
		$result = DebugTimer::stop('non_existent');

		$this->assertFalse($result);
	}

	/**
	 * Test stop without name stops unnamed timer.
	 *
	 * @return void
	 */
	public function testStopWithoutName(): void {
		DebugTimer::start();
		$result = DebugTimer::stop();

		$this->assertTrue($result);
	}

	/**
	 * Test getAll returns all timers.
	 *
	 * @return void
	 */
	public function testGetAll(): void {
		DebugTimer::start('timer1');
		DebugTimer::stop('timer1');
		DebugTimer::start('timer2');
		DebugTimer::stop('timer2');

		$all = DebugTimer::getAll();

		$this->assertArrayHasKey('timer1', $all);
		$this->assertArrayHasKey('timer2', $all);
		$this->assertArrayHasKey('time', $all['timer1']);
	}

	/**
	 * Test getAll with clear.
	 *
	 * @return void
	 */
	public function testGetAllWithClear(): void {
		DebugTimer::start('test');
		DebugTimer::stop('test');

		$all = DebugTimer::getAll(true);
		$this->assertArrayHasKey('test', $all);

		$afterClear = DebugTimer::getAll();
		$this->assertArrayNotHasKey('test', $afterClear);
	}

	/**
	 * Test getAll includes core processing time.
	 *
	 * @return void
	 */
	public function testGetAllIncludesCoreProcessing(): void {
		$all = DebugTimer::getAll();

		$this->assertArrayHasKey('Core Processing (Derived from $_SERVER["REQUEST_TIME"])', $all);
	}

	/**
	 * Test clear removes all timers.
	 *
	 * @return void
	 */
	public function testClear(): void {
		DebugTimer::start('test');
		DebugTimer::stop('test');
		$result = DebugTimer::clear();

		$this->assertTrue($result);

		$all = DebugTimer::getAll();
		$this->assertArrayNotHasKey('test', $all);
	}

	/**
	 * Test elapsedTime returns time.
	 *
	 * @return void
	 */
	public function testElapsedTime(): void {
		DebugTimer::start('test');
		usleep(1000); // 1ms
		DebugTimer::stop('test');

		$elapsed = DebugTimer::elapsedTime('test');

		$this->assertGreaterThan(0, $elapsed);
	}

	/**
	 * Test elapsedTime returns 0 for missing timer.
	 *
	 * @return void
	 */
	public function testElapsedTimeMissing(): void {
		$elapsed = DebugTimer::elapsedTime('non_existent');

		$this->assertSame(0.0, $elapsed);
	}

	/**
	 * Test requestTime returns float.
	 *
	 * @return void
	 */
	public function testRequestTime(): void {
		$time = DebugTimer::requestTime();

		$this->assertIsFloat($time);
		$this->assertGreaterThan(0, $time);
	}

	/**
	 * Test requestStartTime returns float.
	 *
	 * @return void
	 */
	public function testRequestStartTime(): void {
		$time = DebugTimer::requestStartTime();

		$this->assertIsFloat($time);
	}

}
