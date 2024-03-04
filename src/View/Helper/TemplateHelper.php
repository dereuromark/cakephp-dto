<?php

namespace CakeDto\View\Helper;

use Cake\Core\ConventionsTrait;
use Cake\View\Helper;

class TemplateHelper extends Helper {

	use ConventionsTrait;

	/**
	 * Returns an array converted into a formatted multiline string
	 *
	 * @param array $list array of items to be stringified
	 * @param array<string, mixed> $options options to use
	 * @return string
	 */
	public function stringifyList(array $list, array $options = []): string {
		$options += [
			'indent' => 3,
			'tab' => "\t",
			'trailingComma' => true,
		];

		if (!$list) {
			return '';
		}

		foreach ($list as $k => &$v) {
			if (is_string($v)) {
				$v = "'$v'";
			} elseif (is_bool($v)) {
				$v = $v ? 'true' : 'false';
			} elseif ($v === null) {
				$v = 'null';
			}

			if (!is_numeric($k)) {
				$nestedOptions = $options;
				if ($nestedOptions['indent']) {
					$nestedOptions['indent'] += 1;
				}
				if (is_array($v)) {
					$v = sprintf(
						"'%s' => [%s]",
						$k,
						$this->stringifyList($v, $nestedOptions),
					);
				} else {
					$v = "'$k' => $v";
				}
			} elseif (is_array($v)) {
				$nestedOptions = $options;
				if ($nestedOptions['indent']) {
					$nestedOptions['indent'] += 1;
				}
				$v = sprintf(
					'[%s]',
					$this->stringifyList($v, $nestedOptions),
				);
			}
		}

		$start = $end = '';
		$join = ', ';
		if ($options['indent']) {
			$join = ',';
			$start = "\n" . str_repeat($options['tab'], $options['indent']);
			$join .= $start;
			$end = "\n" . str_repeat($options['tab'], $options['indent'] - 1);
		}

		if ($options['trailingComma']) {
			$end = ',' . $end;
		}

		return $start . implode($join, $list) . $end;
	}

}
