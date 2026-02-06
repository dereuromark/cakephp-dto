<?php

namespace CakeDto\Test\TestCase\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use CakeDto\Command\DtoGenerateCommand;
use CakeDto\Filesystem\Folder;
use TestApp\TestSuite\ConsoleOutput;

class DtoGenerateCommandTest extends TestCase {

	/**
	 * @var \CakeDto\Command\DtoGenerateCommand|\PHPUnit\Framework\MockObject\MockObject
	 */
	protected $command;

	/**
	 * @var \TestApp\TestSuite\ConsoleOutput
	 */
	protected $out;

	/**
	 * @var \TestApp\TestSuite\ConsoleOutput
	 */
	protected $err;

	/**
	 * @var \Cake\Console\ConsoleIo
	 */
	protected $io;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$this->io = new ConsoleIo($this->out, $this->err);
		$this->command = $this->getMockBuilder(DtoGenerateCommand::class)->onlyMethods(['_getConfigPath', '_getSrcPath'])->getMock();

		$tmp = TMP . 'command' . DS;
		$folder = new Folder($tmp);
		$folder->delete();
		if (!is_dir($tmp)) {
			mkdir($tmp, 0700, true);
		}

		$this->command->method('_getConfigPath')->willReturn($tmp);
		$this->command->method('_getSrcPath')->willReturn($tmp);
	}

	/**
	 * @return void
	 */
	public function testGenerate() {
		$args = new Arguments([], ['verbose' => true], []); // ['generate', '-v']

		$result = $this->command->execute($args, $this->io);
		$this->assertSame(0, $result);

		//TODO
		//$expected = 'Nothing to generate, please add some DTO(s) into your config file(s).';
		//$this->assertTextContains($expected, $this->err->output());
		$this->assertEmpty($this->err->output(), $this->err->output());

		$this->assertEmpty($this->out->output(), $this->out->output());
	}

}
