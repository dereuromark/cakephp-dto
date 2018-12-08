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
	 * @var array
	 */
	public $output = [];

	/**
	 * Overwrite _write to output the message to debug instead of CLI.
	 *
	 * @param string $message
	 * @return void
	 */
	protected function _write($message) {
		$this->output[] = $message;
	}

	/**
	 * Helper method to return the debug output as string.
	 *
	 * @return string
	 */
	public function output() {
		return implode('', $this->output);
	}

}
