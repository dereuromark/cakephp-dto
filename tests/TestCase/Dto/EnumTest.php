<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\TestSuite\TestCase;
use TestApp\Dto\EnumTestDto;
use TestApp\Model\Enum\MyIntBacked;
use TestApp\Model\Enum\MyStringBacked;
use TestApp\Model\Enum\MyUnit;

class EnumTest extends TestCase {

	/**
	 * @return void
	 */
	public function testEnums() {
		$array = [
			EnumTestDto::FIELD_SOME_UNIT => MyUnit::Y,
			EnumTestDto::FIELD_SOME_STRING_BACKED => MyStringBacked::Bar,
			EnumTestDto::FIELD_SOME_INT_BACKED => MyIntBacked::Foo,
		];
		$dto = new EnumTestDto($array);

		foreach ($array as $field => $value) {
			$v = $dto->get($field);
			$this->assertSame($value, $v);
		}

		$newArray = $dto->toArray();

		// Apparently unit enums dont work otherwise
		$testArray = $array;
		$testArray[EnumTestDto::FIELD_SOME_UNIT] = 'Y';
		$this->assertSame($testArray, $newArray);

		$serialized = $dto->serialize();
		$expected = '{"someUnit":"Y","someStringBacked":"b","someIntBacked":1}';
		$this->assertSame($expected, $serialized);

		$dto = EnumTestDto::fromUnserialized($serialized);
		$this->assertSame($testArray, $dto->toArray());

		foreach ($array as $field => $value) {
			$v = $dto->get($field);
			$this->assertSame($value, $v);
		}
	}

}
