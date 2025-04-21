<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link http://cakephp.org CakePHP(tm) Project
 * @since DebugKit 0.1
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace CakeDto\TestSuite;

use Cake\Error\Debugger;

/**
 * Contains methods for Profiling and creating timers.
 *
 * Copied over from DebugKit to help analyzing
 */
class DebugTimer {

	/**
	 * Internal timers array
	 *
	 * @var array
	 */
	protected static $_timers = [];

	/**
	 * Start an benchmarking timer.
	 *
	 * @param string|null $name The name of the timer to start.
	 * @param string|null $message A message for your timer
	 * @return bool Always true
	 */
	public static function start(?string $name = null, ?string $message = null): bool {
		$start = microtime(true);

		if (!$name) {
			$named = false;
			$calledFrom = debug_backtrace();
			$name = Debugger::trimPath($calledFrom[0]['file'] ?? '') . ' line ' . ($calledFrom[0]['line'] ?? '');
		} else {
			$named = true;
		}

		if (!$message) {
			$message = $name;
		}

		$_name = $name;
		$i = 1;
		while (isset(static::$_timers[$name])) {
			$i++;
			$name = $_name . ' #' . $i;
		}

		if ($i > 1) {
			$message .= ' #' . $i;
		}

		static::$_timers[$name] = [
			'start' => $start,
			'message' => $message,
			'named' => $named,
		];

		return true;
	}

	/**
	 * Stop a benchmarking timer.
	 *
	 * $name should be the same as the $name used in startTimer().
	 *
	 * @param string|null $name The name of the timer to end.
	 * @return bool true if timer was ended, false if timer was not started.
	 */
	public static function stop(?string $name = null): bool {
		$end = microtime(true);
		if (!$name) {
			$names = array_reverse(array_keys(static::$_timers));
			foreach ($names as $name) {
				if (!empty(static::$_timers[$name]['end'])) {
					continue;
				}
				if (empty(static::$_timers[$name]['named'])) {
					break;
				}
			}
		} else {
			$i = 1;
			$_name = $name;
			while (isset(static::$_timers[$name])) {
				if (empty(static::$_timers[$name]['end'])) {
					break;
				}
				$i++;
				$name = $_name . ' #' . $i;
			}
		}
		if (!isset(static::$_timers[$name])) {
			return false;
		}
		static::$_timers[$name]['end'] = $end;

		return true;
	}

	/**
	 * Get all timers that have been started and stopped.
	 * Calculates elapsed time for each timer. If clear is true, will delete existing timers
	 *
	 * @param bool $clear false
	 * @return array
	 */
	public static function getAll(bool $clear = false): array {
		$start = static::requestStartTime();
		$now = microtime(true);

		$times = [];
		if (!empty(static::$_timers)) {
			$firstTimer = reset(static::$_timers);
			$_end = $firstTimer['start'];
		} else {
			$_end = $now;
		}
		$times['Core Processing (Derived from $_SERVER["REQUEST_TIME"])'] = [
			'message' => __d('debug_kit', 'Core Processing (Derived from $_SERVER["REQUEST_TIME"])'),
			'start' => 0,
			'end' => $_end - $start,
			'time' => round($_end - $start, 6),
			'named' => null,
		];
		foreach (static::$_timers as $name => $timer) {
			if (!isset($timer['end'])) {
				$timer['end'] = $now;
			}
			$times[$name] = array_merge($timer, [
				'start' => $timer['start'] - $start,
				'end' => $timer['end'] - $start,
				'time' => static::elapsedTime($name),
			]);
		}
		if ($clear) {
			static::$_timers = [];
		}

		return $times;
	}

	/**
	 * Clear all existing timers
	 *
	 * @return bool true
	 */
	public static function clear(): bool {
		static::$_timers = [];

		return true;
	}

	/**
	 * Get the difference in time between the timer start and timer end.
	 *
	 * @param string $name the name of the timer you want elapsed time for.
	 * @param int $precision the number of decimal places to return, defaults to 5.
	 * @return float number of seconds elapsed for timer name, 0 on missing key
	 */
	public static function elapsedTime(string $name = 'default', int $precision = 5): float {
		if (!isset(static::$_timers[$name]['start']) || !isset(static::$_timers[$name]['end'])) {
			return 0;
		}

		return round(static::$_timers[$name]['end'] - static::$_timers[$name]['start'], $precision);
	}

	/**
	 * Get the total execution time until this point
	 *
	 * @return float elapsed time in seconds since script start.
	 */
	public static function requestTime(): float {
		$start = static::requestStartTime();
		$now = microtime(true);

		return $now - $start;
	}

	/**
	 * get the time the current request started.
	 *
	 * @return float time of request start
	 */
	public static function requestStartTime(): float {
		if (defined('TIME_START')) {
			$startTime = TIME_START;
		} elseif (isset($GLOBALS['TIME_START'])) {
			$startTime = $GLOBALS['TIME_START'];
		} else {
			$startTime = env('REQUEST_TIME');
		}

		return $startTime;
	}

}
