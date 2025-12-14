<?php

namespace CakeDto\Test\TestCase\Generator;

use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakeDto\Console\Io;
use CakeDto\Generator\Builder;
use CakeDto\Generator\Generator;
use CakeDto\View\Renderer;
use PhpCollective\Dto\Engine\XmlEngine;
use PhpCollective\Dto\Filesystem\Folder;
use TestApp\TestSuite\ConsoleOutput;
use TestApp\TestSuite\PhpFileTemplateTestTrait;

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
	public function setUp(): void {
		parent::setUp();

		$this->generator = $this->createGenerator();

		$this->prepareDirectories();
	}

	/**
	 * @return void
	 */
	public function testBasic() {
		$exampleXml = ROOT . DS . 'docs/examples/basic.dto.xml';
		copy($exampleXml, $this->configPath . 'dto.xml');

		Configure::write('CakeDto.scalarAndReturnTypes', false);

		$options = [
			'confirm' => true,
			'verbose' => true,
		];
		$this->generator = $this->createGenerator();
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

		Configure::delete('CakeDto.scalarAndReturnTypes');
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

		Configure::write('CakeDto.scalarAndReturnTypes', false);

		$options = [
			'confirm' => true,
			'force' => true,
			'verbose' => true,
		];
		$this->generator = $this->createGenerator();
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'TreeDto.php';
		$this->assertTemplateContains('TreeDto.constant', $file);
		$this->assertTemplateContains('TreeDto.methods', $file);

		$file = $this->srcPath . 'Dto' . DS . 'AnimalDto.php';
		$this->assertTemplateContains('AnimalDto.dto', $file);
		$this->assertTemplateContainsCount('@deprecated', 1, $file);

		Configure::delete('CakeDto.scalarAndReturnTypes');
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
			'verbose' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'MyForest' . DS . 'MyTreeDto.php';
		$this->assertTemplateContains('Namespaced/MyTreeDto.setValue', $file);

		$file = $this->srcPath . 'Dto' . DS . 'MyForest' . DS . 'MySpecialTreeDto.php';
		$content = file_get_contents($file);
		$expected = <<<TXT
namespace App\Dto\MyForest;

use App\Dto\MyForest\MyTreeDto;

/**
 * MyForest/MySpecialTree DTO
TXT;
		$this->assertTextContains($expected, $content);

		$expected = <<<TXT
 */
class MySpecialTreeDto extends MyTreeDto {
TXT;
		$this->assertTextContains($expected, $content);
	}

	/**
	 * @return void
	 */
	public function testScalarAndReturnTypes() {
		$xml = ROOT . DS . 'docs/examples/basic.dto.xml';
		copy($xml, $this->configPath . 'dto.xml');

		$this->generator = $this->createGenerator();

		$options = [
			'confirm' => true,
			'force' => true,
			'verbose' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'CarDto.php';
		$this->assertTemplateContains('ScalarAndReturnTypes/CarDto.setDistanceTravelled', $file);

		$file = $this->srcPath . 'Dto' . DS . 'FlyingCarDto.php';
		$this->assertTemplateContains('ScalarAndReturnTypes/FlyingCarDto.constant', $file);
		$this->assertTemplateContains('ScalarAndReturnTypes/FlyingCarDto.setMaxAltitude', $file);
		$this->assertTemplateContains('ScalarAndReturnTypes/FlyingCarDto.getMaxSpeed', $file);
		$this->assertTemplateContains('ScalarAndReturnTypes/FlyingCarDto.hasComplexAttributes', $file);
		$this->assertStringNotContainsString(' getMaxAltitudeOrFail(', file_get_contents($file));
		$this->assertStringNotContainsString(' getMaxSpeedOrFail(', file_get_contents($file));
		$this->assertStringContainsString(' getComplexAttributesOrFail(', file_get_contents($file));

		$this->assertStringNotContainsString(' hasMaxAltitude(', file_get_contents($file));
		$this->assertStringNotContainsString(' getMaxSpeedOrFail(', file_get_contents($file));
		$this->assertStringContainsString(' hasComplexAttributes(', file_get_contents($file));

		// Assoc
		$file = $this->srcPath . 'Dto' . DS . 'CarsDto.php';
		$this->assertTemplateContains('ScalarAndReturnTypes/CarsDto.addCar', $file);
	}

	/**
	 * @return void
	 */
	public function testScalarAndReturnTypesDefaultValueRequiredFalse() {
		$xml = ROOT . DS . 'tests/files/xml/scalar_default_required_false.xml';
		copy($xml, $this->configPath . 'dto.xml');

		Configure::write('CakeDto.scalarAndReturnTypes', true);
		$this->generator = $this->createGenerator();

		$options = [
			'confirm' => true,
			'force' => true,
			'verbose' => true,
		];
		$result = $this->generator->generate($this->configPath, $this->srcPath, $options);

		$this->assertSame(2, $result);

		$file = $this->srcPath . 'Dto' . DS . 'DefaultValueDto.php';
		$this->assertTemplateContains('ScalarAndReturnTypes/DefaultValueDto.setDefaultedOptionalField', $file);
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
