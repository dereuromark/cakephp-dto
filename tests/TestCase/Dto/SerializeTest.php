<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\OwnerDto;

class SerializeTest extends TestCase {

	/**
	 * @return void
	 */
	public function testSerialize() {
		$array = [
			'name' => 'My Name',
			'attributes' => [
				'key' => 'x',
				'value' => 'y',
			],
		];
		$owner = new OwnerDto($array);

		$newArray = $owner->toArray();

		$attributes = [
			'key' => 'x',
			'value' => 'y',
		];
		$this->assertSame($attributes, $newArray['attributes']);

		$serialized = $owner->serialize();
		$expected = '{"name":"My Name","attributes":{"key":"x","value":"y"}}';
		$this->assertSame($expected, $serialized);

		$unserialized = OwnerDto::fromUnserialized($serialized);
		$this->assertSame($newArray, $unserialized->toArray());
	}

}
