<?php

namespace CakeDto\Test\TestCase\ValueObject;

use Cake\TestSuite\TestCase;
use TestApp\ValueObject\Paint;

class PaintTest extends TestCase {

	/**
	 * @return void
	 */
	public function testBasic() {
		$result = new Paint(12, 13, 14);

		$expected = [
			'red' => 12,
			'green' => 13,
			'blue' => 14,
		];
		$this->assertSame($expected, $result->toArray());

		$paint = Paint::createFromArray($result->toArray());
		$expectedString = '{"red":12,"green":13,"blue":14}';
		$this->assertSame($expectedString, (string)$paint);

		$newPaint = Paint::createFromString($expectedString);
		$this->assertSame($expected, $newPaint->toArray());
	}

	/**
	 * @return void
	 */
	public function testEquals() {
		$paintOne = new Paint(12, 13, 14);
		$paintTwo = new Paint(12, 13, 14);
		$paintThree = new Paint(24, 26, 28);

		$this->assertFalse($paintOne->equals($paintThree));
		$this->assertTrue($paintOne->equals($paintTwo));

		$paintFour = new Paint(18, 19, 21);
		$this->assertTrue($paintFour->equals($paintOne->mix($paintThree)));
	}

}
