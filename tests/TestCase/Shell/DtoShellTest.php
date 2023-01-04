<?php

namespace CakeDto\Test\TestCase\Shell;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use CakeDto\Filesystem\Folder;
use CakeDto\Shell\DtoShell;
use TestApp\TestSuite\ConsoleOutput;

class DtoShellTest extends TestCase {

	/**
	 * @var \CakeDto\Shell\DtoShell|\PHPUnit\Framework\MockObject\MockObject
	 */
	protected $shell;

	/**
	 * @var \TestApp\TestSuite\ConsoleOutput
	 */
	protected $out;

	/**
	 * @var \TestApp\TestSuite\ConsoleOutput
	 */
	protected $err;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$io = new ConsoleIo($this->out, $this->err);
		$this->shell = $this->getMockBuilder(DtoShell::class)->onlyMethods(['_getConfigPath', '_getSrcPath'])->setConstructorArgs([$io])->getMock();

		$tmp = TMP . 'shell' . DS;
		$folder = new Folder($tmp);
		$folder->delete();
		if (!is_dir($tmp)) {
			mkdir($tmp, 0700, true);
		}

		$this->shell->expects($this->any())->method('_getConfigPath')->willReturn($tmp);
		$this->shell->expects($this->any())->method('_getSrcPath')->willReturn($tmp);
	}

	/**
	 * @return void
	 */
	public function testInit() {
		$result = $this->shell->runCommand(['init', '-v']);
		$this->assertNull($result);

		$expected = 'File generated: dto.xml';
		$this->assertTextContains($expected, $this->out->output());
		$this->assertEmpty($this->err->output(), $this->err->output());
	}

	/**
	 * @return void
	 */
	public function testGenerate() {
		$result = $this->shell->runCommand(['generate', '-v']);
		$this->assertSame(0, $result);

		//TODO
		//$expected = 'Nothing to generate, please add some DTO(s) into your config file(s).';
		//$this->assertTextContains($expected, $this->err->output());
		$this->assertEmpty($this->err->output(), $this->err->output());

		$this->assertEmpty($this->out->output(), $this->out->output());
	}

}
