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

		$unserialized = new OwnerDto();
		$unserialized->unserialize($serialized);
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

	/**
	 * Tests that __unserialize accepts valid known fields.
	 *
	 * @return void
	 */
	public function testUnserializeAcceptsKnownFields(): void {
		$owner = new OwnerDto(['name' => 'Initial']);

		// Should not throw - all fields are known
		$owner->__unserialize([
			'name' => 'Updated Name',
			'attributes' => ['key' => 'value'],
		]);

		$this->assertSame('Updated Name', $owner->getName());
	}

	/**
	 * Tests that __unserialize works with empty data.
	 *
	 * @return void
	 */
	public function testUnserializeWithEmptyData(): void {
		$owner = new OwnerDto(['name' => 'Initial']);

		// Should not throw with empty data
		$owner->__unserialize([]);

		// Name should remain from initial state (since we're unserializing to same instance)
		$this->assertSame('Initial', $owner->getName());
	}

	/**
	 * Tests serialization round-trip preserves data integrity.
	 *
	 * @return void
	 */
	public function testSerializeRoundTripPreservesData(): void {
		$original = new OwnerDto([
			'name' => 'Test Owner',
			'attributes' => ['role' => 'admin', 'level' => '5'],
		]);

		// Serialize and unserialize
		$serialized = serialize($original);
		/** @var \TestApp\Dto\OwnerDto $restored */
		$restored = unserialize($serialized);

		// Data should be preserved
		$this->assertSame($original->getName(), $restored->getName());
		$this->assertEquals($original->getAttributes(), $restored->getAttributes());
		$this->assertEquals($original->touchedToArray(), $restored->touchedToArray());
	}

	/**
	 * Tests that __serialize returns only touched fields.
	 *
	 * @return void
	 */
	public function testSerializeReturnsOnlyTouchedFields(): void {
		$owner = new OwnerDto(['name' => 'Test']);

		$serializedData = $owner->__serialize();

		// Should only contain 'name' as it's the only touched field
		$this->assertArrayHasKey('name', $serializedData);
		$this->assertArrayNotHasKey('attributes', $serializedData);
		$this->assertArrayNotHasKey('insuranceId', $serializedData);
	}

}
