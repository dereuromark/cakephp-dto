<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\MutableMetaDto;

class AssociativeTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testNumericKey() {
		$array = [
			MutableMetaDto::FIELD_TITLE => 'blue',
		];
		$dto = new MutableMetaDto($array);
		$dto->addMetaValue('abc', 'abcdef');
		$dto->addMetaValue('123', '123456');

		$result = $dto->getMetaValue('123');
		$this->assertSame('123456', $result);

		$result = $dto->getMeta();
		$expected = [
			'abc' => 'abcdef',
			123 => '123456',
		];
		$this->assertSame($expected, $result);
	}

}
