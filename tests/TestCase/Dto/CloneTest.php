<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use TestApp\Dto\BookDto;
use TestApp\Dto\CarDto;
use TestApp\Dto\CarsDto;
use TestApp\Dto\OwnerDto;
use TestApp\Dto\PageDto;

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

	/**
	 * Tests cloneArrayObject() - cloning DTOs with ArrayObject collections.
	 *
	 * @return void
	 */
	public function testCloneWithArrayObjectCollection(): void {
		$carsDto = new CarsDto();
		$car1 = new CarDto(['isNew' => true, 'value' => 10000.00]);
		$car2 = new CarDto(['isNew' => false, 'value' => 5000.00]);
		$carsDto->addCar('car1', $car1);
		$carsDto->addCar('car2', $car2);

		$clonedCarsDto = $carsDto->clone();

		// Verify the collections have same count
		$this->assertSame(2, $clonedCarsDto->getCars()->count());

		// Verify the data is the same
		$this->assertSame(10000.00, $clonedCarsDto->getCar('car1')->getValue());
		$this->assertSame(5000.00, $clonedCarsDto->getCar('car2')->getValue());

		// Verify they are different instances (ArrayObject and nested DTOs)
		$this->assertNotSame($carsDto->getCars(), $clonedCarsDto->getCars());
		$this->assertNotSame($carsDto->getCar('car1'), $clonedCarsDto->getCar('car1'));
	}

	/**
	 * Tests that modifying cloned ArrayObject collection doesn't affect original.
	 *
	 * @return void
	 */
	public function testCloneArrayObjectModificationDoesNotAffectOriginal(): void {
		$carsDto = new CarsDto();
		$car1 = new CarDto(['isNew' => true, 'value' => 10000.00]);
		$carsDto->addCar('car1', $car1);

		$clonedCarsDto = $carsDto->clone();

		// Modify the cloned car's value
		$clonedCarsDto->getCar('car1')->setValue(20000.00);

		// Original should be unchanged
		$this->assertSame(10000.00, $carsDto->getCar('car1')->getValue());
		$this->assertSame(20000.00, $clonedCarsDto->getCar('car1')->getValue());
	}

	/**
	 * Tests cloneCollection() - cloning DTOs with Cake Collection.
	 *
	 * @return void
	 */
	public function testCloneWithCakeCollection(): void {
		// Build book via withAddedPage to properly populate the collection
		$page1 = new PageDto(['number' => 1, 'content' => 'Chapter 1']);

		$bookDto = (new BookDto())->withAddedPage($page1);

		$clonedBookDto = $bookDto->clone();

		// Verify the collections have same count
		$this->assertSame(1, $clonedBookDto->getPages()->count());

		// Verify the data is the same
		$clonedPages = $clonedBookDto->getPages()->toArray();
		$this->assertSame(1, $clonedPages[0]->getNumber());
		$this->assertSame('Chapter 1', $clonedPages[0]->getContent());

		// Verify they are different instances
		$originalPages = $bookDto->getPages()->toArray();
		$this->assertNotSame($originalPages[0], $clonedPages[0]);
	}

	/**
	 * Tests cloning with nested DTOs inside arrays.
	 *
	 * @return void
	 */
	public function testCloneWithNestedDtosInArray(): void {
		$owner1 = new OwnerDto(['name' => 'Owner 1']);
		$owner2 = new OwnerDto(['name' => 'Owner 2']);

		$car = new CarDto([
			'isNew' => true,
			'owner' => $owner1,
		]);

		$clonedCar = $car->clone();

		// Verify nested DTO is cloned
		$this->assertNotSame($car->getOwner(), $clonedCar->getOwner());
		$this->assertSame('Owner 1', $clonedCar->getOwner()->getName());

		// Modify cloned owner
		$clonedCar->getOwner()->setName('Modified Owner');

		// Original unchanged
		$this->assertSame('Owner 1', $car->getOwner()->getName());
	}

	/**
	 * Tests cloning with non-DTO objects (should use native clone).
	 *
	 * @return void
	 */
	public function testCloneWithNonDtoObject(): void {
		$date = new Date('2023-01-15');
		$car = new CarDto([
			'isNew' => true,
			'manufactured' => $date,
		]);

		$clonedCar = $car->clone();

		// Verify date is cloned (not same instance)
		$this->assertNotSame($car->getManufactured(), $clonedCar->getManufactured());
		$this->assertSame($car->getManufactured()->format('Y-m-d'), $clonedCar->getManufactured()->format('Y-m-d'));
	}

}
