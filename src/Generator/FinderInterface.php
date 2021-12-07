<?php

namespace CakeDto\Generator;

interface FinderInterface {

	/**
	 * Find dto specification files
	 *
	 * Should return an array of file paths
	 *
	 * @param string $configPath
	 * @param string $extension
	 * @return array<string>
	 */
	public function collect(string $configPath, string $extension): array;
	
}
