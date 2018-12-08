<?php

namespace TestApp\TestSuite;

use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/**
 * @mixin \Cake\TestSuite\TestCase
 */
trait PhpFileTemplateTestTrait {

	/**
	 * @param string $template
	 * @param string $file
	 * @return void
	 * @throws \RuntimeException
	 */
	protected function assertTemplateContains($template, $file) {
		$content = trim(file_get_contents($file));

		list($folder, $template) = explode('.', $template);
		$needle = ROOT . DS . 'tests' . DS . 'files' . DS . $folder . DS . $template . '.txt';
		if (!file_exists($needle)) {
			throw new RuntimeException('Invalid template `' . $folder . '.' . $template . '`: ' . $needle . ' not found.');
		}
		$needle = trim(file_get_contents($needle));

		try {
			$this->assertTextContains($needle, $content, $folder . '.' . $template . ' does not match file ' . $file);
		} catch (ExpectationFailedException $e) {
			$differ = new Differ(new UnifiedDiffOutputBuilder("\n--- Expected\n+++ Actual\n"));

			$diff = $differ->diff($needle, $content);
			$lines = explode("\n", trim($diff));
			$count = count($lines);
			$begin = 0;
			$end = null;
			for ($i = 3; $i < $count; $i++) {
				if (substr($lines[$i], 0, 1) !== '+') {
					$begin = $i;
					break;
				}
			}
			for ($i = $count - 1; $i > $begin; $i--) {
				if (substr($lines[$i], 0, 1) !== '+') {
					$end = $i;
					break;
				}
			}

			if ($begin) {
				$begin = max(0, $begin - 8);

				if (!$end) {
					$end = $count - 1;
				} else {
					$end = max(0, $end + 8);
				}
				$lines = array_slice($lines, $begin, $end);
			}

			$dir = TMP . 'compare' . DS . $folder . DS;
			if (!is_dir($dir)) {
				mkdir($dir, 0700, true);
			}

			file_put_contents($dir . $template . '.expected', $needle);
			file_put_contents($dir . $template . '.result', $content);
			file_put_contents($dir . $template . '.diff', implode("\n", $lines));

			throw $e;
		}
	}

	/**
	 * @param string $needle
	 * @param int $count
	 * @param string $file
	 * @return void
	 */
	protected function assertTemplateContainsCount($needle, $count, $file) {
		$content = file_get_contents($file);

		$actualCount = mb_substr_count($content, $needle);
		$this->assertSame($count, $actualCount, '`' . $needle . '` actually occurs ' . $actualCount . 'x');
	}

}
