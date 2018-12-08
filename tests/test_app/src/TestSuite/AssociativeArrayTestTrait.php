<?php

namespace TestApp\TestSuite;

/**
 * @mixin \Cake\TestSuite\TestCase
 */
trait AssociativeArrayTestTrait {

	/**
	 * @param array $expected
	 * @param array $result
	 * @return void
	 */
	protected function assertAssociativeArray(array $expected, array $result) {
		foreach ($expected as $key => $value) {
			$this->assertTrue(isset($result[$key]), $key . ' missing in ' . print_r($result, true));

			if (is_array($value) && is_array($result[$key])) {
				$this->assertAssociativeArray($value, $result[$key]);
				continue;
			}

			$this->assertSame($value, $result[$key]);
		}
	}

	/**
	 * @param array $expected
	 * @param array $is
	 * @return void
	 */
	protected function assertAssociativeArrayEquals(array $expected, array $is) {
		ksort($expected);
		ksort($is);

		$this->assertEquals($expected, $is);
	}

	/**
	 * @param array $expected
	 * @param array $is
	 * @return void
	 */
	protected function assertAssociativeArraySame(array $expected, array $is) {
		ksort($expected);
		ksort($is);

		$this->assertSame($expected, $is);
	}

}
