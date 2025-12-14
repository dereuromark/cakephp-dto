<?php

namespace CakeDto\Console;

use Cake\Command\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\Exception\StopException;
use PhpCollective\Dto\Generator\IoInterface;

/**
 * Composition class as proxy towards ConsoleIO - basically a shell replacement for inside business logic.
 * Implements the standalone library's IoInterface for compatibility.
 */
class Io implements IoInterface {

	/**
	 * @var \Cake\Console\ConsoleIo
	 */
	protected $_io;

	/**
	 * @param \Cake\Console\ConsoleIo $io
	 */
	public function __construct(ConsoleIo $io) {
		$this->_io = $io;
	}

	/**
	 * Output at the verbose level.
	 *
	 * @param array<string>|string $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @return int|null The number of bytes returned from writing to stdout.
	 */
	public function verbose($message, int $newlines = 1) {
		return $this->_io->verbose($message, $newlines);
	}

	/**
	 * Output at all levels.
	 *
	 * @param array<string>|string $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @return int|null The number of bytes returned from writing to stdout.
	 */
	public function quiet($message, int $newlines = 1) {
		return $this->_io->quiet($message, $newlines);
	}

	/**
	 * Outputs a single or multiple messages to stdout. If no parameters
	 * are passed outputs just a newline.
	 *
	 * @link http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::out
	 * @param string|null $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @param int $level The message's output level, see above.
	 * @return int|null The number of bytes returned from writing to stdout.
	 */
	public function out($message = null, int $newlines = 1, int $level = ConsoleIo::NORMAL) {
		return $this->_io->out((string)$message, $newlines, $level);
	}

	/**
	 * Outputs a single or multiple error messages to stderr. If no parameters
	 * are passed outputs just a newline.
	 *
	 * @param string|null $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @return int|null The number of bytes returned from writing to stderr.
	 */
	public function error($message = null, int $newlines = 1) {
		return $this->_io->error((string)$message, $newlines);
	}

	/**
	 * Convenience method for out() that wraps message between <info /> tag
	 *
	 * @see http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::out
	 * @param string|null $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @param int $level The message's output level, see above.
	 * @return int|null The number of bytes returned from writing to stdout.
	 */
	public function info($message = null, int $newlines = 1, int $level = ConsoleIo::NORMAL) {
		return $this->_io->info((string)$message, $level);
	}

	/**
	 * Convenience method for out() that wraps message between <comment /> tag
	 *
	 * @see http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::out
	 * @param string|null $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @param int $level The message's output level, see above.
	 * @return int|null The number of bytes returned from writing to stdout.
	 */
	public function comment($message = null, int $newlines = 1, int $level = ConsoleIo::NORMAL) {
		return $this->_io->comment((string)$message, $level);
	}

	/**
	 * Convenience method for err() that wraps message between <warning /> tag
	 *
	 * @see http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::err
	 * @param string|null $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @return int|null The number of bytes returned from writing to stderr.
	 */
	public function warn($message = null, int $newlines = 1) {
		return $this->_io->warning((string)$message, $newlines);
	}

	/**
	 * Convenience method for out() that wraps message between <success /> tag
	 *
	 * @see http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::out
	 * @param string|null $message A string or an array of strings to output
	 * @param int $newlines Number of newlines to append
	 * @param int $level The message's output level, see above.
	 * @return int|null The number of bytes returned from writing to stdout.
	 */
	public function success($message = null, int $newlines = 1, int $level = ConsoleIo::NORMAL) {
		return $this->_io->success((string)$message, $level);
	}

	/**
	 * Returns a single or multiple linefeeds sequences.
	 *
	 * @link http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::nl
	 * @param int $multiplier Number of times the linefeed sequence should be repeated
	 * @return string
	 */
	public function nl(int $multiplier = 1): string {
		return $this->_io->nl($multiplier);
	}

	/**
	 * Outputs a series of minus characters to the standard output, acts as a visual separator.
	 *
	 * @link http://book.cakephp.org/3.0/en/console-and-shells.html#Shell::hr
	 * @param int $newlines Number of newlines to pre- and append
	 * @param int $width Width of the line, defaults to 63
	 * @return void
	 */
	public function hr(int $newlines = 0, int $width = 63): void {
		$this->_io->hr($newlines, $width);
	}

	/**
	 * Displays a formatted error message
	 * and exits the application with status code 1
	 *
	 * @link http://book.cakephp.org/3.0/en/console-and-shells.html#styling-output
	 * @param string $message The error message
	 * @param int $exitCode The exit code for the shell task.
	 * @throws \Cake\Console\Exception\StopException
	 * @return void
	 */
	public function abort(string $message, int $exitCode = Command::CODE_ERROR): void {
		$this->_io->error($message);

		throw new StopException($message, $exitCode);
	}

}
