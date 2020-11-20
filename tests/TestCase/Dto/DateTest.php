<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\OwnerDto;
use TestApp\ValueObject\Birthday;

class DateTest extends TestCase {

	/**
	 * @return void
	 */
	public function testFromArray() {
		$array = [
			'name' => 'My Name',
			'birthday' => '2011-01-26',
		];
		$owner = new OwnerDto($array);

		$array = $owner->toArray();

		$this->assertInstanceOf(Birthday::class, $array['birthday']);
		$json = json_encode($array);

		$expected = '{"name":"My Name","insuranceProvider":null,"attributes":null,"birthday":"1\/26\/11"}';
		$this->assertSame($expected, $json);

		$newArray = json_decode($expected, true);
		$expected = '1/26/11';
		$this->assertSame($expected, $newArray['birthday']);

		$newCustomerAccount = new OwnerDto($newArray);
		$this->assertEquals($array['birthday'], $newCustomerAccount->getBirthdayOrFail());

		$result = $newCustomerAccount->getBirthdayOrFail()->toArray();
		$expectedArray = [
			'year' => 2011,
			'month' => 1,
			'day' => 26,
		];
		$this->assertSame($expectedArray, $result);
	}

}
