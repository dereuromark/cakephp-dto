<?php

namespace CakeDto\Generator;

use Cake\Console\ConsoleIo;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

trait DiffHelperTrait {

	/**
	 * Outputs some debug info for tests.
	 *
	 * @param string $expected
	 * @param string $actual
	 *
	 * @return void
	 */
	/**
	 * @param string $oldContent
	 * @param string $newContent
	 * @return void
	 */
	protected function _displayDiff(string $oldContent, string $newContent): void {
		$differ = new Differ(new DiffOnlyOutputBuilder());
		$array = $differ->diffToArray($oldContent, $newContent);

		$begin = null;
		$end = null;
		/**
		 * @var int $key
		 */
		foreach ($array as $key => $row) {
			if ($row[1] === 0) {
				continue;
			}

			if ($begin === null) {
				$begin = $key;
			}
			$end = $key;
		}
		if ($begin === null) {
			return;
		}
		$firstLineOfOutput = $begin > 0 ? $begin - 1 : 0;
		$lastLineOfOutput = count($array) - 1 > $end ? $end + 1 : $end;

		for ($i = $firstLineOfOutput; $i <= $lastLineOfOutput; $i++) {
			$row = $array[$i];

			$char = ' ';
			$output = trim($row[0], "\n\r\0\x0B");

			if ($row[1] === 1) {
				$char = '+';
				$this->io->info('   | ' . $char . $output, 1, ConsoleIo::VERBOSE);
			} elseif ($row[1] === 2) {
				$char = '-';
				$this->io->out('<warning>' . '   | ' . $char . $output . '</warning>', 1, ConsoleIo::VERBOSE);
			} else {
				$this->io->out('   | ' . $char . $output, 1, ConsoleIo::VERBOSE);
			}
		}
	}

}
