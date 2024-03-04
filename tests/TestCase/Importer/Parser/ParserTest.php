<?php

namespace CakeDto\Test\TestCase\Importer\Parser;

use Cake\TestSuite\TestCase;
use CakeDto\Importer\Importer;
use DirectoryIterator;

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

			$array = (new Importer())->parse($content);

			$schemas = (new Importer())->buildSchema($array);
			$this->assertNotEmpty($schemas);

			$schema = implode("\n\n", $schemas);

			$this->assertStringContainsString('<dto name="', $schema, $file . ': ' . $schema);
			//$this->assertStringContainsString('<field name="', $schema, $file . ': ' . $schema);
		}
	}

}
