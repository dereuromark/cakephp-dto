<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use RuntimeException;
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

	/**
	 * @return void
	 */
	public function testUnserializeRejectsUnknownFields(): void {
		$owner = new OwnerDto(['name' => 'Test']);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("Unknown field(s) 'maliciousField' in serialized data for TestApp\Dto\OwnerDto");

		// Directly call __unserialize with data containing unknown fields
		$owner->__unserialize([
			'name' => 'Test',
			'maliciousField' => 'attack',
		]);
	}

	/**
	 * @return void
	 */
	public function testUnserializeRejectsMultipleUnknownFields(): void {
		$owner = new OwnerDto(['name' => 'Test']);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("Unknown field(s) 'badField1', 'badField2' in serialized data for TestApp\Dto\OwnerDto");

		$owner->__unserialize([
			'name' => 'Test',
			'badField1' => 'value1',
			'badField2' => 'value2',
		]);
	}

}
