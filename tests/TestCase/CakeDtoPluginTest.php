<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase;

use Cake\Console\CommandCollection;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakeDto\CakeDtoPlugin;
use PhpCollective\Dto\Importer\Importer;
use PhpCollective\Dto\Importer\Parser\Config as ParserConfig;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(CakeDtoPlugin::class)]
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

	public function testConfiguredAssociationKeysAffectImporter(): void {
		$previous = ParserConfig::keyFields();
		Configure::write('CakeDto.assocKeyFields', ['code']);
		try {
			$this->plugin->bootstrap($this->createStub(PluginApplicationInterface::class));
			$definitions = (new Importer())->parse('{"items":[{"code":"one","label":"First"},{"code":"two","label":"Second"}]}');

			$this->assertSame('code', $definitions['Object']['items']['associative']);
		} finally {
			Configure::delete('CakeDto.assocKeyFields');
			ParserConfig::setKeyFields($previous);
		}
	}

}
