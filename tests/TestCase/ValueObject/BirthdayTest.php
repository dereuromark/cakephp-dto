<?php

namespace CakeDto\Test\TestCase\ValueObject;

use Cake\I18n\FrozenDate;
use Cake\TestSuite\TestCase;
use TestApp\ValueObject\Birthday;

class BirthdayTest extends TestCase {

	/**
	 * @return void
	 */
	public function testBasic() {
		$date = new FrozenDate(date('Y-m-d'));
		$result = new Birthday($date);

		$expected = [
			'year' => (int)date('Y'),
			'month' => (int)date('m'),
			'day' => (int)date('d'),
		];
		$this->assertSame($expected, $result->toArray());

		$birthday = Birthday::createFromArray($result->toArray());
		$expectedString = $expected['month'] . '/' . $expected['day'] . '/' . substr($expected['year'], -2);
		$this->assertSame($expectedString, (string)$birthday);

		$newBirthday = Birthday::createFromString($expectedString);
		$this->assertSame($expected, $newBirthday->toArray());
	}

	/**
	 * @return void
	 */
	public function testAge() {
		$date = new FrozenDate(date('Y-m-d'));
		$birthday = new Birthday($date);

		$age = $birthday->getAge();
		$this->assertSame(0, $age);

		$date = (new FrozenDate())->subYears(2);
		$birthday = new Birthday($date);

		$age = $birthday->getAge();
		$this->assertSame(2, $age);
	}

}
