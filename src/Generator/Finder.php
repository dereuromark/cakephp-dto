<?php

namespace CakeDto\Generator;

use DirectoryIterator;
use InvalidArgumentException;

class Finder {

	/**
	 * @param string $configPath
	 * @param string $extension
	 * @throws \InvalidArgumentException
	 * @return string[]
	 */
	public function collect(string $configPath, string $extension): array {
		$files = [];
		if (is_dir($configPath . 'dto')) {
			$iterator = new DirectoryIterator($configPath . 'dto');
			foreach ($iterator as $fileInfo) {
				if ($fileInfo->isDot()) {
					continue;
				}

				$file = $fileInfo->getPathname();
				if (!preg_match('/^\w+\.dto\.' . $extension . '$/', $fileInfo->getFilename())) {
					throw new InvalidArgumentException('Invalid config file name: ' . $fileInfo->getFilename());
				}
				$files[] = $file;
			}
		}
		if (file_exists($configPath . 'dto.' . $extension)) {
			$files[] = $configPath . 'dto.' . $extension;
		}

		return $files;
	}

}
