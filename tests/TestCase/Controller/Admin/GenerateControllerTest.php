<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Controller\Admin;

use Cake\Core\Configure;
use Cake\Database\Connection;
use Cake\Database\Driver\Sqlite;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakeDto\Controller\Admin\GenerateController;
use DOMDocument;
use PHPUnit\Framework\Attributes\UsesClass;
use RuntimeException;

#[UsesClass(GenerateController::class)]
class GenerateControllerTest extends TestCase {

	use IntegrationTestTrait;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->loadPlugins(['CakeDto']);
		$this->disableErrorHandlerMiddleware();
		Configure::write('CakeDto.adminAccess', fn (): bool => true);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		Configure::delete('CakeDto.adminAccess');
		parent::tearDown();
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

	/**
	 * Default-deny: with `CakeDto.adminAccess` unset, the gate must reject every admin request.
	 *
	 * @return void
	 */
	public function testIndexDeniedByDefault(): void {
		Configure::delete('CakeDto.adminAccess');
		$this->expectException(ForbiddenException::class);

		$this->get(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'index']);
	}

	/**
	 * A non-Closure value for the gate config must also be rejected.
	 *
	 * @return void
	 */
	public function testIndexDeniedWhenAdminAccessIsNotAClosure(): void {
		Configure::write('CakeDto.adminAccess', true);
		$this->expectException(ForbiddenException::class);

		$this->get(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'index']);
	}

	/**
	 * A Closure that returns a non-true value (false, truthy non-bool, null) must be rejected.
	 *
	 * @return void
	 */
	public function testIndexDeniedWhenClosureReturnsNonTrue(): void {
		Configure::write('CakeDto.adminAccess', fn (): bool => false);
		$this->expectException(ForbiddenException::class);

		$this->get(['prefix' => 'Admin', 'plugin' => 'CakeDto', 'controller' => 'Generate', 'action' => 'index']);
	}

	public function testAdminRoot(): void {
		$this->get('/admin/cake-dto');

		$this->assertResponseCode(200);
	}

	public function testInvalidJsonShowsFeedback(): void {
		$this->post('/admin/cake-dto/generate/schema', ['input' => '{invalid', 'type' => 'json']);

		$this->assertResponseCode(200);
		$this->assertResponseContains('message error');
		$this->assertResponseContains('Syntax error');
	}

	public function testExplicitHttpExceptionIsPreserved(): void {
		Configure::write('CakeDto.adminAccess', static function (): never {
			throw new UnauthorizedException('Sign in first');
		});
		$this->expectException(UnauthorizedException::class);
		$this->expectExceptionMessage('Sign in first');

		$this->get('/admin/cake-dto');
	}

	public function testAuthorizationServiceIsSkippedWithoutComponent(): void {
		$service = new class {
			public bool $skipped = false;

			public function skipAuthorization(): void {
				$this->skipped = true;
			}
		};
		$request = (new ServerRequest())->withAttribute('authorization', $service);
		$controller = new GenerateController($request);

		$controller->beforeFilter(new Event('Controller.initialize', $controller));

		$this->assertTrue($service->skipped);
	}

	public function testUnexpectedGateFailureDeniesAccess(): void {
		Configure::write('CakeDto.adminAccess', static function (): never {
			throw new RuntimeException('Private credentials');
		});
		$this->expectException(ForbiddenException::class);
		$this->expectExceptionMessage('Not authorized to access CakeDto admin UI');

		$this->get('/admin/cake-dto');
	}

	public function testImportedXmlUsesPluginSchema(): void {
		$this->post('/admin/cake-dto/generate/schema', [
			'dto' => ['Product' => ['title' => ['type' => 'string']]],
			'format' => 'xml',
		]);

		$this->assertResponseCode(200);
		$result = $this->viewVariable('result');
		$this->assertIsString($result);
		$xml = new DOMDocument();
		$previous = libxml_use_internal_errors(true);
		try {
			$this->assertTrue($xml->loadXML($result));
			$this->assertTrue($xml->schemaValidate(ROOT . DS . 'config/dto.xsd'));
		} finally {
			libxml_clear_errors();
			libxml_use_internal_errors($previous);
		}
	}

}
