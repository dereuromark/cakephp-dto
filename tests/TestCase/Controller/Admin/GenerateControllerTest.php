<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Controller\Admin;

use Cake\Database\Connection;
use Cake\Database\Driver\Sqlite;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @uses \CakeDto\Controller\Admin\GenerateController
 */
class GenerateControllerTest extends TestCase {

	use IntegrationTestTrait;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->loadPlugins(['CakeDto']);
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

	/**
	 * @return void
	 */
	public function testDatabase() {
		ConnectionManager::setConfig('default', [
			'className' => Connection::class,
			'driver' => Sqlite::class,
			'database' => ':memory:',
		]);

		$connection = ConnectionManager::get('default');
		/** @var \Cake\Database\Connection $connection */
		$connection->execute('CREATE TABLE articles (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			title VARCHAR(255) NOT NULL
		)');

		$this->get(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'database']);

		$this->assertResponseCode(200);
		$this->assertResponseContains('articles');

		ConnectionManager::drop('default');
	}

	/**
	 * @return void
	 */
	public function testDatabasePostTables() {
		ConnectionManager::setConfig('default', [
			'className' => Connection::class,
			'driver' => Sqlite::class,
			'database' => ':memory:',
		]);

		$connection = ConnectionManager::get('default');
		/** @var \Cake\Database\Connection $connection */
		$connection->execute('CREATE TABLE articles (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			title VARCHAR(255) NOT NULL,
			body TEXT
		)');

		$this->post(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'database'], [
			'tables' => ['articles' => '1'],
		]);

		$this->assertResponseCode(200);
		$this->assertResponseContains('Article');
		$this->assertResponseContains('title');

		ConnectionManager::drop('default');
	}

}
