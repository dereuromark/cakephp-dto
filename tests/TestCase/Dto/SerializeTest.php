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

	/**
	 * @return void
	 */
	public function testSerializeMagic() {
		$this->skipIf(version_compare(PHP_VERSION, '7.4.0', '<'), 'Require PHP 7.4+ to work properly.');

		$array = [
			'name' => 'My Name',
			'attributes' => [
				'key' => 'x',
				'value' => 'y',
			],
		];
		$owner = new OwnerDto($array);

		$result = serialize($owner);
		$this->assertTrue(is_string($result));

		/** @var \TestApp\Dto\OwnerDto $owner */
		$owner = unserialize($result);
		$newArray = $owner->touchedToArray();
		$this->assertSame($array, $newArray);
	}

}
