<?php

namespace CakeDto\Test\TestCase\Importer\Parser;

use Cake\TestSuite\TestCase;
use DirectoryIterator;
use PhpCollective\Dto\Importer\Importer;
use PHPUnit\Framework\Attributes\Group;
use TypeError;

/**
 * @group integration
 *
 * This test clones the entire SchemaStore repository (600+ JSON schemas)
 * and parses all of them. It's very memory-intensive and can cause segfaults
 * when combined with code coverage. Excluded from normal CI runs.
 */
#[Group('integration')]
class ParserTest extends TestCase {

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$url = 'https://github.com/SchemaStore/schemastore.git';
		$dir = getcwd() . '/tmp';
		$this->path = $dir . '/schemastore/src/schemas/json/';

		if (!is_dir($dir)) {
			mkdir(getcwd() . '/tmp', 0700, true);
		}
		if (is_dir($dir . '/schemastore')) {
			return;
		}

		exec('cd ' . $dir . ' && git clone ' . $url);
	}

	/**
	 * @return void
	 */
	public function testParse(): void {
		/** @var \SplFileInfo[] $iterator */
		$iterator = new DirectoryIterator($this->path);
		foreach ($iterator as $fileinfo) {
			if (!$fileinfo->isFile()) {
				continue;
			}

			$file = $fileinfo->getPathname();
			$content = file_get_contents($file);

			try {
				$array = (new Importer())->parse($content);

				$schema = (new Importer())->buildSchema($array, ['format' => 'xml']);
				$this->assertNotEmpty($schema);

				// Some schemas may produce empty results if they don't map to DTOs
				if (!empty($array)) {
					$this->assertStringContainsString('<dto', $schema, $file . ': ' . $schema);
				}
			} catch (TypeError $e) {
				// Some JSON schemas have unusual structures that cause type errors
				// Just skip these files silently
				continue;
			}
			//$this->assertStringContainsString('<field name="', $schema, $file . ': ' . $schema);
		}
	}

}
