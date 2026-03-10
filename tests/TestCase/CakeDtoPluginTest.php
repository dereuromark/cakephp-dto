<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase;

use Cake\Console\CommandCollection;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakeDto\CakeDtoPlugin;

/**
 * @uses \CakeDto\CakeDtoPlugin
 */
class CakeDtoPluginTest extends TestCase {

	/**
	 * @var \CakeDto\CakeDtoPlugin
	 */
	protected CakeDtoPlugin $plugin;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->plugin = new CakeDtoPlugin();
	}

	/**
	 * @return void
	 */
	protected function tearDown(): void {
		Router::reload();

		parent::tearDown();
	}

	/**
	 * Test that bootstrap is enabled by default.
	 *
	 * @return void
	 */
	public function testBootstrapEnabled(): void {
		$this->assertTrue($this->plugin->isEnabled('bootstrap'));
	}

	/**
	 * Test that middleware is disabled by default.
	 *
	 * @return void
	 */
	public function testMiddlewareDisabled(): void {
		$this->assertFalse($this->plugin->isEnabled('middleware'));
	}

	/**
	 * Test routes method registers admin routes.
	 *
	 * @return void
	 */
	public function testRoutes(): void {
		$builder = Router::createRouteBuilder('/');
		$this->plugin->routes($builder);

		$routes = Router::routes();
		$this->assertNotEmpty($routes);
	}

	/**
	 * Test console method registers commands.
	 *
	 * @return void
	 */
	public function testConsole(): void {
		$commands = new CommandCollection();
		$result = $this->plugin->console($commands);

		$this->assertInstanceOf(CommandCollection::class, $result);
		$this->assertTrue($result->has('dto init'));
		$this->assertTrue($result->has('dto generate'));
	}

}
