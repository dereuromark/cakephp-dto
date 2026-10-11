<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Console;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use CakeDto\Console\Io;
use PHPUnit\Framework\Attributes\DataProvider;
use TestApp\TestSuite\ConsoleOutput;

class IoTest extends TestCase {

	/**
	 * @return array<string, array<string>>
	 */
	public static function formattedOutputMethods(): array {
		return ['info' => ['info'], 'comment' => ['comment'], 'success' => ['success']];
	}

	#[DataProvider('formattedOutputMethods')]
	public function testFormattedOutputHonorsVerbosityAndNewlines(string $method): void {
		$out = new ConsoleOutput();
		$out->setOutputAs(ConsoleOutput::PLAIN);
		$console = new ConsoleIo($out, new ConsoleOutput());
		$io = new Io($console);

		$io->{$method}('Hidden', 2, ConsoleIo::VERBOSE);
		$this->assertSame('', $out->output());
		$console->level(ConsoleIo::VERBOSE);
		$io->{$method}('Shown', 2, ConsoleIo::VERBOSE);
		$this->assertSame('Shown' . PHP_EOL . PHP_EOL, $out->output());
	}

}
