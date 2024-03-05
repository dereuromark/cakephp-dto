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

		$newArray = $dto->toArray();

		// Apparently unit enums dont work otherwise
		$array[EnumTestDto::FIELD_SOME_UNIT] = 'Y';
		$this->assertSame($array, $newArray);

		$serialized = $dto->serialize();
		$expected = '{"someUnit":"Y","someStringBacked":"b","someIntBacked":1}';
		$this->assertSame($expected, $serialized);

		$dto = EnumTestDto::fromUnserialized($serialized);
		$this->assertSame($array, $dto->toArray());
	}

}
