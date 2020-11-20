<?php

namespace CakeDto\Test\TestCase\ValueObject;

use Cake\TestSuite\TestCase;
use TestApp\ValueObject\KeyValuePair;

class KeyValuePairTest extends TestCase {

	/**
	 * @return void
	 */
	public function testBasic() {
		$result = new KeyValuePair(['key' => 'x', 'value' => 'y']);

		$expected = [
			'key' => 'x',
			'value' => 'y',
		];
		$this->assertSame($expected, $result->toArray());

		$expectedString = '{"key":"x","value":"y"}';
		$this->assertSame($expectedString, $result->serialize());

		$newKeyValuePair = KeyValuePair::createFromString($expectedString);
		$this->assertSame($expected, $newKeyValuePair->toArray());
	}

}
