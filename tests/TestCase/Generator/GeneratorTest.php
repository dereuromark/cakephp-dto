<?php

namespace CakeDto\Test\TestCase\Generator;

use CakeDto\Console\Io;
use CakeDto\Engine\XmlEngine;
use CakeDto\Generator\Builder;
use CakeDto\Generator\Generator;
use CakeDto\View\Renderer;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
use TestApp\TestSuite\ConsoleOutput;
use TestApp\TestSuite\PhpFileTemplateTestTrait;
use WyriHaximus\TwigView\Event\ExtensionsListener;
use WyriHaximus\TwigView\Event\TokenParsersListener;

class GeneratorTest extends TestCase {

	use PhpFileTemplateTestTrait;

	/**
	 * @var \CakeDto\Generator\Generator
	 */
	protected $generator;

	/**
	 * @var \TestApp\TestSuite\ConsoleOutput
	 */
	protected $out;

	/**
	 * @var \TestApp\TestSuite\ConsoleOutput
	 */
	protected $err;

	/**
	 * @var string
	 */
	protected $configPath;

	/**
	 * @var string
	 */
	protected $srcPath;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Configure::write('CakeDto.scalarTypeHints', false);

		// Why needed?
		EventManager::instance()->on(new ExtensionsListener());
		EventManager::instance()->on(new TokenParsersListener());

		$this->generator = $this->createGenerator();

		$this->prepareDirectories();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->builder);

		Configure::write('CakeDto.scalarTypeHints', false);
	}

	/**
	 * @return void
	 */
	public function testBasic() {
		$exampleXml = ROOT . DS . 'docs/examples/basic.dto.xml';
		copy($exampleXml, $this->configPath . 'dto.xml');

		$options = [
			'confirm' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(0, $result);
		$this->assertEmpty($this->err->output());
		$expected = 'Creating: Car DTO';
		$this->assertTextContains($expected, $this->out->output());
		$expected = 'Skipping: Car DTO';
		$this->assertTextContains($expected, $this->out->output());

		$file = $this->srcPath . 'Dto' . DS . 'CarDto.php';
		$this->assertTemplateContains('CarDto.get', $file);
		$this->assertTemplateContains('CarDto.set', $file);
		$this->assertTemplateContains('CarDto.has', $file);
		$this->assertTemplateContains('CarDto.get_or_fail', $file);
	}

	/**
	 * @return void
	 */
	protected function prepareDirectories() {
		$configPath = TMP . 'config' . DS;
		$folder = new Folder($configPath);
		$folder->delete();
		if (!is_dir($configPath)) {
			mkdir($configPath, 0700, true);
		}
		$srcPath = TMP . 'src' . DS;
		$folder = new Folder($srcPath . 'Dto' . DS);
		$folder->delete();
		if (!is_dir($srcPath . 'Dto' . DS)) {
			mkdir($srcPath . 'Dto' . DS, 0700, true);
		}

		$this->configPath = $configPath;
		$this->srcPath = $srcPath;
	}

	/**
	 * @return void
	 */
	public function testDeprecated() {
		$xml = ROOT . DS . 'tests/files/xml/deprecated.xml';
		copy($xml, $this->configPath . 'dto.xml');

		$options = [
			'confirm' => true,
			'force' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'TreeDto.php';
		$this->assertTemplateContains('TreeDto.constant', $file);
		$this->assertTemplateContains('TreeDto.methods', $file);

		$file = $this->srcPath . 'Dto' . DS . 'AnimalDto.php';
		$this->assertTemplateContains('AnimalDto.dto', $file);
		$this->assertTemplateContainsCount('@deprecated', 1, $file);
	}

	/**
	 * @return void
	 */
	public function testNamespaced() {
		$xml = ROOT . DS . 'tests/files/xml/namespaced.xml';
		copy($xml, $this->configPath . 'dto.xml');

		$options = [
			'confirm' => true,
			'force' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'MyForest' . DS . 'MySpecialTreeDto.php';
		$content = file_get_contents($file);
		$expected = <<<TXT
namespace App\Dto\MyForest;

/**
 * MyForest/MySpecialTree DTO
 */
class MySpecialTreeDto extends \App\Dto\MyForest\MyTreeDto {
TXT;
		$this->assertTextContains($expected, $content);
	}

	/**
	 * @return void
	 */
	public function testScalarTypeHints() {
		$this->skipIf(version_compare(PHP_VERSION, '7.1') < 0, 'Requires PHP 7.1+');

		$xml = ROOT . DS . 'docs/examples/basic.dto.xml';
		copy($xml, $this->configPath . 'dto.xml');

		Configure::write('CakeDto.scalarTypeHints', true);
		$this->generator = $this->createGenerator();

		$options = [
			'confirm' => true,
			'force' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'CarDto.php';
		$this->assertTemplateContains('ScalarTypeHints/CarDto.setDistanceTravelled', $file);

		$file = $this->srcPath . 'Dto' . DS . 'FlyingCarDto.php';
		$this->assertTemplateContains('ScalarTypeHints/FlyingCarDto.setMaxAltitude', $file);
		$this->assertTemplateContains('ScalarTypeHints/FlyingCarDto.setMaxSpeed', $file);
		$this->assertNotContains(' getMaxAltitudeOrFail(', file_get_contents($file));
		$this->assertNotContains(' getMaxSpeedOrFail(', file_get_contents($file));
		$this->assertContains(' getComplexAttributesOrFail(', file_get_contents($file));

		$this->assertNotContains(' hasMaxAltitude(', file_get_contents($file));
		$this->assertNotContains(' getMaxSpeedOrFail(', file_get_contents($file));
		$this->assertContains(' hasComplexAttributes(', file_get_contents($file));
	}

	/**
	 * @return void
	 */
	public function testScalarTypeHintsDefaultValueRequiredFalse() {
		$this->skipIf(version_compare(PHP_VERSION, '7.1') < 0, 'Requires PHP 7.1+');

		$xml = ROOT . DS . 'tests/files/xml/scalar_default_required_false.xml';
		copy($xml, $this->configPath . 'dto.xml');

		Configure::write('CakeDto.scalarTypeHints', true);
		$this->generator = $this->createGenerator();

		$options = [
			'confirm' => true,
			'force' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'DefaultValueDto.php';
		$this->assertTemplateContains('ScalarTypeHints/DefaultValueDto.setDefaultedOptionalField', $file);
	}

	/**
	 * @return \CakeDto\Generator\Generator
	 */
	protected function createGenerator() {
		$engine = new XmlEngine();
		$builder = new Builder($engine);
		$renderer = new Renderer();

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$consoleIo = new ConsoleIo($this->out, $this->err);
		$consoleIo->level($consoleIo::VERBOSE);
		$io = new Io($consoleIo);

		return new Generator($builder, $renderer, $io);
	}

}
