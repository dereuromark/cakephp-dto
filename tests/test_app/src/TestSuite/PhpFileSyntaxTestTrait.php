<?php

namespace TestApp\TestSuite;

/**
 * @mixin \Cake\TestSuite\TestCase
 */
trait PhpFileSyntaxTestTrait {

	/**
	 * @param string $file
	 * @return void
	 */
	protected function assertPhpFileValid($file) {
		exec('php -l "' . $file . '"', $output, $returnValue);

		$this->assertSame(0, $returnValue, 'PHP file invalid: ' . implode("\n", $output));
	}

}
