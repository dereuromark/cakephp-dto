<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\CarDto;
use TestApp\Dto\OwnerDto;

class MutableTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function test() {
		$ownerDto = new OwnerDto();
		$ownerDto->setName('The Owner');

		$carDto = new CarDto();
		$carDto->setOwner($ownerDto);

		$otherCarDto = $carDto;

		// A trivial example
		$otherCarDto->getOwner()->setName('The new owner');

		// You might not expect the original $carDto to also change it's value... Hopefully you do :)
		$this->assertSame('The new owner', $otherCarDto->getOwner()->getName());
		$this->assertSame('The new owner', $carDto->getOwner()->getName());
	}

}
