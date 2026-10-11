<?php

namespace TestApp\TestSuite;

use Cake\Console\ConsoleOutput as CakeConsoleOutput;

/**
 * Use for testing as
 *
 *  use TestApp\TestSuite\ConsoleOutput;
 *
 *  $stdOut = new ConsoleOutput();
 *  $stdErr = new ConsoleOutput();
 *  $io = new ConsoleIo($stdOut, $stdErr);
 *  $this->Shell = $this->getMock(
 *    'App\Shell\FooBarShell',
 *    ['in', '_stop'],
 *    [$io]
 *  );
 *
 * @license MIT
 * @author Mark Scherer
 */
class ConsoleOutput extends CakeConsoleOutput {

	/**
	 * Holds all output messages.
	 *
	 * @var array<string>
	 */
	public array $messages = [];

	/**
	 * Override writeStream to output the message to debug instead of CLI.
	 *
	 * @param string $message
	 * @return int
	 */
	protected function writeStream(string $message): int {
		$this->messages[] = $message;

		return 0;
	}

	/**
	 * Helper method to return the debug output as string.
	 *
	 * @return string
	 */
	public function output(): string {
		return implode('', $this->messages);
	}

}
