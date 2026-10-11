<?php

namespace CakeDto\Test\TestCase\Generator;

use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakeDto\Console\Io;
use CakeDto\Filesystem\Folder;
use CakeDto\Generator\Builder;
use CakeDto\Generator\Generator;
use CakeDto\View\Renderer;
use PhpCollective\Dto\Engine\XmlEngine;
use ReflectionMethod;
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
	 * Regression: `findExistingDtos()` matched `#src/Dto/(.+)<suffix>\.php$#`
	 * which hardcodes forward slashes. On Windows the iterator yields paths
	 * with backslashes and the regex never matched — so the "delete DTOs no
	 * longer in the spec" pass silently no-op'd, leaving stale generated
	 * files behind. The replacement pattern accepts either separator.
	 *
	 * Exercising the Windows path directly on a Linux CI box is awkward, so
	 * we drive the now-DS-tolerant regex itself with both shapes via a tiny
	 * temp-dir round trip that fakes a Windows-style nested path.
	 *
	 * @return void
	 */
	public function testFindExistingDtosToleratesEitherDirectorySeparator(): void {
		// Use reflection to reach the protected method; the alternative would
		// be exposing it just for tests.
		$method = new ReflectionMethod(Generator::class, 'findExistingDtos');

		// Linux/macOS path layout — both DTO files should be found and indexed
		// under their plain camel-case name.
		$dir = TMP . 'dto-fed-test-' . uniqid();
		mkdir($dir . '/src/Dto/Nested', 0777, true);
		file_put_contents($dir . '/src/Dto/FooDto.php', '<?php');
		file_put_contents($dir . '/src/Dto/Nested/BarDto.php', '<?php');

		try {
			$result = $method->invoke($this->generator, $dir . '/src/Dto');
			$keys = array_keys($result);
			sort($keys);
			$this->assertSame(['Foo', 'Nested/Bar'], $keys);

			// The captured key for nested entries is normalized to a forward
			// slash, regardless of the host OS, so callers can use it as a
			// stable map key.
			$this->assertArrayHasKey('Nested/Bar', $result);
		} finally {
			array_map('unlink', glob($dir . '/src/Dto/*.php') ?: []);
			array_map('unlink', glob($dir . '/src/Dto/Nested/*.php') ?: []);
			@rmdir($dir . '/src/Dto/Nested');
			@rmdir($dir . '/src/Dto');
			@rmdir($dir . '/src');
			@rmdir($dir);
		}
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
		// maxAltitude has defaultValue but no required=true, so it's nullable and has OrFail/has methods
		$this->assertStringContainsString(' getMaxAltitudeOrFail(', file_get_contents($file));
		// maxSpeed has defaultValue AND required=true, so it's non-nullable and has no OrFail/has methods
		$this->assertStringNotContainsString(' getMaxSpeedOrFail(', file_get_contents($file));
		$this->assertStringContainsString(' getComplexAttributesOrFail(', file_get_contents($file));

		$this->assertStringContainsString(' hasMaxAltitude(', file_get_contents($file));
		$this->assertStringNotContainsString(' hasMaxSpeed(', file_get_contents($file));
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

	public function testDryRunDetectsOnlyObsoleteFiles(): void {
		copy(ROOT . DS . 'files/dto.xml', $this->configPath . 'dto.xml');
		$file = $this->srcPath . 'Dto' . DS . 'ObsoleteDto.php';
		file_put_contents($file, '<?php');

		$result = $this->generator->generate($this->configPath, $this->srcPath, ['dryRun' => true]);

		$this->assertSame(Generator::CODE_CHANGES, $result);
		$this->assertFileExists($file);
	}

	public function testDryRunDoesNotCreateOutputDirectory(): void {
		copy(ROOT . DS . 'files/dto.xml', $this->configPath . 'dto.xml');
		(new Folder($this->srcPath . 'Dto'))->delete();

		$result = $this->generator->generate($this->configPath, $this->srcPath, ['dryRun' => true]);

		$this->assertSame(Generator::CODE_SUCCESS, $result);
		$this->assertDirectoryDoesNotExist($this->srcPath . 'Dto');
	}

	public function testDryRunWithDefinitionsDoesNotCreateOutputDirectory(): void {
		copy(ROOT . DS . 'docs/examples/basic.dto.xml', $this->configPath . 'dto.xml');
		(new Folder($this->srcPath . 'Dto'))->delete();

		$result = $this->generator->generate($this->configPath, $this->srcPath, ['dryRun' => true]);

		$this->assertSame(Generator::CODE_CHANGES, $result);
		$this->assertDirectoryDoesNotExist($this->srcPath . 'Dto');
	}

	public function testCustomOutputPathRecognizesUnchangedAndObsoleteFiles(): void {
		$srcPath = TMP . 'generated' . DS;
		(new Folder($srcPath))->delete();
		copy(ROOT . DS . 'docs/examples/basic.dto.xml', $this->configPath . 'dto.xml');

		try {
			$this->assertSame(Generator::CODE_CHANGES, $this->generator->generate($this->configPath, $srcPath, ['verbose' => true]));
			$this->assertSame(Generator::CODE_SUCCESS, $this->generator->generate($this->configPath, $srcPath, ['dryRun' => true]));
			copy(ROOT . DS . 'files/dto.xml', $this->configPath . 'dto.xml');
			$this->assertSame(Generator::CODE_CHANGES, $this->generator->generate($this->configPath, $srcPath, ['dryRun' => true]));
		} finally {
			(new Folder($srcPath))->delete();
		}
	}

	public function testExampleConfigurationGeneratesSuccessfully(): void {
		$config = Configure::read('CakeDto');
		Configure::write(require ROOT . DS . 'config/app.example.php');
		copy(ROOT . DS . 'docs/examples/basic.dto.xml', $this->configPath . 'dto.xml');
		try {
			$generator = $this->createGenerator();
			$this->assertSame(Generator::CODE_SUCCESS, $generator->generate($this->configPath, $this->srcPath, ['confirm' => true]));
		} finally {
			Configure::write('CakeDto', $config);
		}
	}

	public function testStringDefaultsWithQuotesAndBackslashes(): void {
		file_put_contents($this->configPath . 'dto.xml', <<<'XML'
<?xml version="1.0"?>
<dtos xmlns="cakephp-dto">
    <dto name="Quoted">
        <field name="title" type="string" defaultValue="O&apos;Reilly\Books"/>
    </dto>
</dtos>
XML);

		$result = $this->generator->generate($this->configPath, $this->srcPath, ['confirm' => true, 'namespace' => 'TestApp']);

		$this->assertSame(Generator::CODE_SUCCESS, $result);
		require $this->srcPath . 'Dto' . DS . 'QuotedDto.php';
		$className = 'TestApp\\Dto\\QuotedDto';
		$dto = new $className();
		$this->assertSame("O'Reilly\\Books", $dto->getTitle());
	}

	public function testNullableUnionWorksAtRuntime(): void {
		file_put_contents($this->configPath . 'dto.xml', <<<'XML'
<?xml version="1.0"?>
<dtos xmlns="cakephp-dto">
    <dto name="UnionReview">
        <field name="reference" type="int|string"/>
    </dto>
</dtos>
XML);
		$this->assertSame(Generator::CODE_SUCCESS, $this->generator->generate($this->configPath, $this->srcPath, ['confirm' => true, 'namespace' => 'TestApp']));
		require $this->srcPath . 'Dto' . DS . 'UnionReviewDto.php';
		$className = 'TestApp\\Dto\\UnionReviewDto';
		$dto = new $className();
		$this->assertNull($dto->getReference());
		$dto->setReference(3);
		$this->assertSame(3, $dto->getReference());
		$dto->setReference('external');
		$this->assertSame('external', $dto->getReferenceOrFail());
		$dto->setReference(null);
		$this->assertNull($dto->getReference());
	}

	public function testFactoriesAcceptMethodsAndExistingObjects(): void {
		file_put_contents($this->configPath . 'dto.xml', <<<'XML'
<?xml version="1.0"?>
<dtos xmlns="cakephp-dto">
    <dto name="FactoryReview">
        <field name="created" type="\Cake\I18n\DateTime" factory="parse"/>
        <field name="updated" type="\Cake\I18n\DateTime" factory="\Cake\I18n\DateTime::parse"/>
    </dto>
</dtos>
XML);
		$this->assertSame(Generator::CODE_SUCCESS, $this->generator->generate($this->configPath, $this->srcPath, ['confirm' => true, 'namespace' => 'TestApp']));
		require $this->srcPath . 'Dto' . DS . 'FactoryReviewDto.php';
		$className = 'TestApp\\Dto\\FactoryReviewDto';
		$dto = new $className(['created' => '2026-01-02 03:04:05', 'updated' => '2026-02-03 04:05:06']);
		$this->assertSame('2026-01-02', $dto->getCreated()->format('Y-m-d'));
		$this->assertSame('2026-02-03', $dto->getUpdated()->format('Y-m-d'));
		$dto->fromArray(['created' => '2026-03-04 05:06:07']);
		$this->assertSame('2026-03-04', $dto->getCreated()->format('Y-m-d'));
		$value = $dto->getCreated();
		$dto->fromArray(['created' => $value]);
		$this->assertSame($value, $dto->getCreated());
	}

	public function testCustomFieldMappingRoundTrips(): void {
		file_put_contents($this->configPath . 'dto.xml', <<<'XML'
<?xml version="1.0"?>
<dtos xmlns="cakephp-dto">
    <dto name="MappingReview">
        <field name="firstName" type="string" mapFrom="first_name" mapTo="first_name"/>
    </dto>
</dtos>
XML);
		$this->assertSame(Generator::CODE_SUCCESS, $this->generator->generate($this->configPath, $this->srcPath, ['confirm' => true, 'namespace' => 'TestApp']));
		require $this->srcPath . 'Dto' . DS . 'MappingReviewDto.php';
		$className = 'TestApp\\Dto\\MappingReviewDto';
		$dto = $className::createFromArray(['first_name' => 'Bob']);
		$this->assertSame('Bob', $dto->getFirstName());
		$this->assertSame(['first_name' => 'Bob'], $dto->toArray());
		$dto->fromArray(['first_name' => 'Ann']);
		$this->assertSame('Ann', $dto->getFirstName());
		$this->assertSame(['first_name' => 'Ann'], $dto->toArray($dto::TYPE_UNDERSCORED));
	}

	public function testConfirmReturnsErrorForInvalidGeneratedPhp(): void {
		copy(ROOT . DS . 'docs/examples/basic.dto.xml', $this->configPath . 'dto.xml');
		$renderer = $this->getMockBuilder(Renderer::class)->onlyMethods(['generate'])->getMock();
		$renderer->expects($this->atLeastOnce())->method('generate')->willReturn('<?php invalid PHP code');
		$generator = new Generator(new Builder(new XmlEngine()), $renderer, new Io(new ConsoleIo($this->out, $this->err)));

		$result = $generator->generate($this->configPath, $this->srcPath, ['confirm' => true]);

		$this->assertSame(Generator::CODE_ERROR, $result);
		$this->assertTextContains('PHP file invalid:', $this->err->output());
	}

}
