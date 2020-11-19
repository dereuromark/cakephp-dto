<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\MutableMetaDto;

class AssociativeTest extends TestCase {

	/**
	 * Shows PHP internal handling of string keys that are "numeric".
	 *
	 * They auto-transform to int array keys, as such careful with strict comparison afterwards.
	 *
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

	/**
	 * Tests that numeric values are OKish to be used as associative array keys.
	 *
	 * @return void
	 */
	public function testNumericMerge() {
		$array = [
			MutableMetaDto::FIELD_TITLE => 'blue',
		];
		$dto = new MutableMetaDto($array);

		$metaValues = [
			123 => '123a',
			234 => '234a',
			345 => '345a',
		] + [
			234 => '234b',
			456 => '456b',
		];
		$this->assertSame('234a', $metaValues[234]);

		$dto->addMetaValue(234, '234b');
		$dto->addMetaValue(456, '456b');

		$dto->addMetaValue(123, '123a');
		$dto->addMetaValue(234, '234a');
		$dto->addMetaValue(345, '345a');

		$result = $dto->getMetaValue(234);
		$this->assertSame('234a', $result);

		$result = $dto->getMeta();
		ksort($result);
		ksort($metaValues);
		$this->assertSame($metaValues, $result);
	}

}
