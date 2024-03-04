<?php
declare(strict_types=1);

namespace CakeCto\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @uses \Queue\Controller\Admin\QueueController
 */
class GenerateControllerTest extends TestCase {

	use IntegrationTestTrait;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->disableErrorHandlerMiddleware();
	}

	/**
	 * @return void
	 */
	public function testIndex() {
		$this->get(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'index']);

		$this->assertResponseCode(200);
	}

	/**
	 * @return void
	 */
	public function testSchema() {
		$this->get(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'schema']);

		$this->assertResponseCode(200);
	}

}
