<?php

namespace CakeDto\Test\TestCase\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\Exception\StopException;
use Cake\TestSuite\TestCase;
use CakeDto\Command\DtoInitCommand;
use PhpCollective\Dto\Filesystem\Folder;
use TestApp\TestSuite\ConsoleOutput;

class DtoInitCommandTest extends TestCase {

	/**
	 * @var \CakeDto\Command\DtoInitCommand|\PHPUnit\Framework\MockObject\MockObject
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
		$this->command = $this->getMockBuilder(DtoInitCommand::class)->onlyMethods(['_getConfigPath'])->getMock();

		$tmp = TMP . 'command' . DS;
		$folder = new Folder($tmp);
		$folder->delete();
		if (!is_dir($tmp)) {
			mkdir($tmp, 0700, true);
		}

		$this->command->expects($this->any())->method('_getConfigPath')->willReturn($tmp);
	}

	/**
	 * @return void
	 */
	public function testInit() {
		$args = new Arguments([], ['verbose' => true], []); // ['init', '-v']

		$result = $this->command->execute($args, $this->io);
		$this->assertNull($result);

		$expected = 'File generated: dto.xml';
		$this->assertTextContains($expected, $this->out->output());
		$this->assertEmpty($this->err->output(), $this->err->output());

		$this->expectException(StopException::class);
		$this->expectExceptionMessage('Command aborted');

		$this->command->execute($args, $this->io);
	}

	/**
	 * @return void
	 */
	public function testInitForced() {
		$args = new Arguments([], ['verbose' => true, 'force' => true], []); // ['init', '-v', '-f']

		$result = $this->command->execute($args, $this->io);
		$this->assertNull($result);

		$expected = 'File generated: dto.xml';
		$this->assertTextContains($expected, $this->out->output());
		$this->assertEmpty($this->err->output(), $this->err->output());

		$result = $this->command->execute($args, $this->io);
		$this->assertNull($result);

		$expected = 'File re-generated: dto.xml';
		$this->assertTextContains($expected, $this->out->output());
		$this->assertEmpty($this->err->output(), $this->err->output());
	}

}
