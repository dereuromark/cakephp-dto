<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\CarDto;
use TestApp\Dto\OwnerDto;

class CloneTest extends TestCase {

	/**
	 * @return void
	 */
	public function testCloneCreatesIndependentCopy(): void {
		$ownerData = [
			'name' => 'John Doe',
		];
		$carData = [
			'isNew' => true,
			'value' => 25000.00,
			'owner' => $ownerData,
		];
		$car = new CarDto($carData);

		$clonedCar = $car->clone();

		// Verify they have the same data
		$this->assertSame($car->getValue(), $clonedCar->getValue());
		$this->assertSame($car->getIsNew(), $clonedCar->getIsNew());
		$this->assertSame($car->getOwner()->getName(), $clonedCar->getOwner()->getName());

		// Verify they are different instances
		$this->assertNotSame($car, $clonedCar);
		$this->assertNotSame($car->getOwner(), $clonedCar->getOwner());
	}

	/**
	 * @return void
	 */
	public function testCloneNestedDtoModificationDoesNotAffectOriginal(): void {
		$owner = new OwnerDto(['name' => 'Original Owner']);
		$car = new CarDto([
			'isNew' => true,
			'owner' => $owner,
		]);

		$clonedCar = $car->clone();

		// Modify the cloned car's owner
		$clonedCar->getOwner()->setName('Modified Owner');

		// Original should be unchanged
		$this->assertSame('Original Owner', $car->getOwner()->getName());
		$this->assertSame('Modified Owner', $clonedCar->getOwner()->getName());
	}

	/**
	 * @return void
	 */
	public function testClonePreservesTouchedFields(): void {
		$car = new CarDto();
		$car->setIsNew(true);
		$car->setValue(15000.00);

		$clonedCar = $car->clone();

		$this->assertSame($car->touchedFields(), $clonedCar->touchedFields());
	}

	/**
	 * @return void
	 */
	public function testCloneWithArrayField(): void {
		$car = new CarDto([
			'attributes' => ['fast', 'red', 'convertible'],
		]);

		$clonedCar = $car->clone();

		// Verify arrays have same content
		$this->assertSame($car->getAttributes(), $clonedCar->getAttributes());

		// Modify the cloned car's attributes
		$clonedCar->setAttributes(['slow', 'blue']);

		// Original should be unchanged
		$this->assertSame(['fast', 'red', 'convertible'], $car->getAttributes());
		$this->assertSame(['slow', 'blue'], $clonedCar->getAttributes());
	}

	/**
	 * @return void
	 */
	public function testCloneWithNullFields(): void {
		$car = new CarDto([
			'isNew' => true,
		]);

		$clonedCar = $car->clone();

		$this->assertTrue($clonedCar->getIsNew());
		$this->assertNull($clonedCar->getOwner());
		$this->assertNull($clonedCar->getValue());
	}

}
