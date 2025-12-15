<?php

declare(strict_types=1);

namespace CakeDto\Test\TestCase\Generator;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakeDto\Generator\CakeConfig;
use PhpCollective\Dto\Generator\ConfigInterface;
use PhpCollective\Dto\Generator\Finder;

class CakeConfigTest extends TestCase {

	/**
	 * @var \CakeDto\Generator\CakeConfig
	 */
	protected CakeConfig $config;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->config = new CakeConfig();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
		Configure::delete('CakeDto');
	}

	/**
	 * @return void
	 */
	public function testImplementsInterface(): void {
		$this->assertInstanceOf(ConfigInterface::class, $this->config);
	}

	/**
	 * @return void
	 */
	public function testDefaultValues(): void {
		$config = new CakeConfig();

		$this->assertTrue($config->get('scalarAndReturnTypes'));
		$this->assertFalse($config->get('typedConstants'));
		$this->assertSame('\ArrayObject', $config->get('defaultCollectionType'));
		$this->assertFalse($config->get('debug'));
		$this->assertFalse($config->get('immutable'));
		$this->assertSame(Finder::class, $config->get('finder'));
		$this->assertSame('Dto', $config->get('suffix'));
	}

	/**
	 * @return void
	 */
	public function testCustomValues(): void {
		Configure::write('CakeDto.suffix', 'Data');
		Configure::write('CakeDto.immutable', true);
		Configure::write('CakeDto.debug', true);

		$config = new CakeConfig();

		$this->assertSame('Data', $config->get('suffix'));
		$this->assertTrue($config->get('immutable'));
		$this->assertTrue($config->get('debug'));
	}

	/**
	 * @return void
	 */
	public function testGetWithDefault(): void {
		$config = new CakeConfig();

		$this->assertNull($config->get('nonexistent'));
		$this->assertSame('default', $config->get('nonexistent', 'default'));
	}

	/**
	 * @return void
	 */
	public function testAll(): void {
		$config = new CakeConfig();

		$all = $config->all();
		$this->assertIsArray($all);
		$this->assertArrayHasKey('scalarAndReturnTypes', $all);
		$this->assertArrayHasKey('typedConstants', $all);
		$this->assertArrayHasKey('defaultCollectionType', $all);
		$this->assertArrayHasKey('debug', $all);
		$this->assertArrayHasKey('immutable', $all);
		$this->assertArrayHasKey('finder', $all);
		$this->assertArrayHasKey('suffix', $all);
		$this->assertArrayHasKey('namespace', $all);
	}

	/**
	 * @return void
	 */
	public function testNamespaceFromConfigure(): void {
		Configure::write('App.namespace', 'MyApp');

		$config = new CakeConfig();

		$this->assertSame('MyApp', $config->get('namespace'));
	}

}
