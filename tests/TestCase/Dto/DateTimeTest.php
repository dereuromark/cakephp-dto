<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use TestApp\Dto\CustomerAccountDto;

class DateTimeTest extends TestCase {

	/**
	 * @return void
	 */
	public function testFromArray() {
		$array = [
			'customerName' => 'My title',
			'lastLogin' => new DateTime('2011-01-26T19:01:12Z'),
		];
		$customerAccount = new CustomerAccountDto($array);

		$array = $customerAccount->toArray();
		$this->assertInstanceOf(DateTime::class, $array['lastLogin']);
		$json = json_encode($array);

		$expected = '{"customerName":"My title","birthYear":null,"lastLogin":"2011-01-26T19:01:12+00:00"}';
		$this->assertSame($expected, $json);

		$newArray = json_decode($expected, true);
		$this->assertSame('2011-01-26T19:01:12+00:00', $newArray['lastLogin']);

		$newCustomerAccount = new CustomerAccountDto($newArray);
		$this->assertEquals($array['lastLogin'], $newCustomerAccount->getLastLogin());
	}

}
