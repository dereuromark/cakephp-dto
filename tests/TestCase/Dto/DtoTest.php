<?php

namespace CakeDto\Test\TestCase\Dto;

use ArrayObject;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use RuntimeException;
use TestApp\Dto\CarDto;
use TestApp\Dto\CarsDto;
use TestApp\Dto\FlyingCarDto;
use TestApp\Dto\OwnerDto;
use TestApp\ValueObject\Paint;

class DtoTest extends TestCase {

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
	public function testFromArray() {
		$array = [
			CarDto::FIELD_COLOR => 'blue',
			CarDto::FIELD_DISTANCE_TRAVELLED => 33,
		];

		$dto = new CarDto($array);

		$this->assertSame($array[CarDto::FIELD_DISTANCE_TRAVELLED], $dto->getDistanceTravelledOrFail());
	}

	/**
	 * @return void
	 */
	public function testFromArrayDashed() {
		$array = [
			'color' => 'blue',
			'distance-travelled' => 66,
		];

		$dto = new CarDto($array, false, CarDto::TYPE_DASHED);

		$this->assertSame($array['distance-travelled'], $dto->getDistanceTravelledOrFail());
	}

	/**
	 * @return void
	 */
	public function testFromArrayUnderscored() {
		$array = [
			'color' => 'blue',
			'distance_travelled' => 66,
		];

		$dto = new CarDto($array, false, CarDto::TYPE_UNDERSCORED);

		$this->assertSame($array['distance_travelled'], $dto->getDistanceTravelledOrFail());
	}

	/**
	 * @return void
	 */
	public function testIgnoreMissing() {
		$array = [
			'foooo' => 'baaaaar',
			'color' => 'blue',
			'distanceTravelled' => 66,
		];
		$dto = new CarDto($array, true, CarDto::TYPE_DEFAULT);
		$this->assertInstanceOf(CarDto::class, $dto);

		$this->expectException(InvalidArgumentException::class);

		new CarDto($array);
	}

	/**
	 * @return void
	 */
	public function testToArray() {
		$dto = new CarDto();
		$dto->setDistanceTravelled(11);

		$result = $dto->toArray();
		$expected = [
			'color' => null,
			'attributes' => null,
			'isNew' => null,
			'distanceTravelled' => 11,
			'value' => null,
			'manufactured' => null,
			'owner' => null,
			'service' => null,
		];
		ksort($expected);
		ksort($result);
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testToArrayDashed() {
		$dto = new CarDto();
		$dto->setDistanceTravelled(11);

		$result = $dto->toArray($dto::TYPE_DASHED);
		$expected = [
			'color' => null,
			'attributes' => null,
			'is-new' => null,
			'distance-travelled' => 11,
			'value' => null,
			'manufactured' => null,
			'owner' => null,
			'service' => null,
		];
		ksort($expected);
		ksort($result);
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testToArrayDashedAndParent() {
		$car = new CarDto();
		$car->setDistanceTravelled(11);

		$owner = new OwnerDto();
		$owner->setBirthYear(1960);

		$car->setOwner($owner);

		$result = $car->toArray($car::TYPE_DASHED);
		$expected = [
			'color' => null,
			'attributes' => null,
			'is-new' => null,
			'distance-travelled' => 11,
			'value' => null,
			'manufactured' => null,
			'owner' => [
				'name' => null,
				'birth-year' => 1960,
			],
			'service' => null,
		];
		ksort($expected);
		ksort($result);
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testToArrayDashedAndChildren() {
		$car = new CarDto();
		$car->setDistanceTravelled(11);

		$collection = new ArrayObject();
		$collection->append($car);

		$cars = new CarsDto();
		$cars->setCars($collection);

		$array = $cars->toArray($cars::TYPE_DASHED);
		$expected = [
			'color' => null,
			'attributes' => null,
			'is-new' => null,
			'distance-travelled' => 11,
			'value' => null,
			'owner' => null,
			'manufactured' => null,
			'service' => null,
		];
		ksort($expected);
		$result = $array['cars'][0];
		ksort($result);
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testToArrayCollection() {
		$carsDto = new CarsDto();
		$carOne = new CarDto(['distanceTravelled' => 123]);
		$carTwo = new CarDto(['distanceTravelled' => 234]);
		$carThree = new CarDto(['distanceTravelled' => 345]);

		$carsDto->addCar('one', $carOne);
		$carsDto->addCar('two', $carTwo);
		$carsDto->addCar('three', $carThree);

		$result = $carsDto->touchedToArray();

		$expected = [
			'cars' => [
				'one' => [
					'distanceTravelled' => 123
				],
				'two' => [
					'distanceTravelled' => 234
				],
				'three' => [
					'distanceTravelled' => 345
				]
			]
		];
		$this->assertSame($expected, $result);
		$this->assertSame($carOne, $carsDto->getCar('one'));
		$this->assertSame($carTwo, $carsDto->getCar('two'));
		$this->assertSame($carThree, $carsDto->getCar('three'));
	}

	/**
	 * @return void
	 */
	public function testTouchedToArray() {
		$dto = new CarDto();

		$result = $dto->touchedToArray();
		$this->assertSame([], $result);

		$dto->setDistanceTravelled(11);

		$result = $dto->touchedToArray();
		$expected = [
			'distanceTravelled' => 11,
		];
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testToString() {
		$carDto = new CarDto();
		$carDto->setDistanceTravelled(11);

		$result = (string)$carDto;
		$expected = '{"distanceTravelled":11}';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testSerialize() {
		$carDto = new CarDto();
		// Let's add an immutable Value Object
		$color = new Paint(12, 13, 14);
		$carDto->setColor($color);

		$carDto->setDistanceTravelled(11);
		$ownerDto = new OwnerDto();
		$ownerDto->setName('Foo');
		$carDto->setOwner($ownerDto);

		$result = $carDto->serialize();

		$expected = '{"color":{"red":12,"green":13,"blue":14},"distanceTravelled":11,"owner":{"name":"Foo"}}';
		$this->assertSame($expected, $result);

		$carDto->unserialize($expected);
		$this->assertSame(11, $carDto->getDistanceTravelledOrFail());

		$this->assertInstanceOf(Paint::class, $carDto->getColorOrFail());
		$this->assertTrue($color->equals($carDto->getColorOrFail()));

		$this->assertInstanceOf(OwnerDto::class, $carDto->getOwnerOrFail());
		$this->assertSame('Foo', $carDto->getOwner()->getNameOrFail());
	}

	/**
	 * @return void
	 */
	public function testSerializeCollection() {
		$carsDto = new CarsDto();
		$carsDto->addCar('one', new CarDto(['distanceTravelled' => 123]));
		$carsDto->addCar('two', new CarDto(['distanceTravelled' => 234]));
		$carsDto->addCar('three', new CarDto(['distanceTravelled' => 345]));

		$result = $carsDto->serialize();

		$expected = '{"cars":{"one":{"distanceTravelled":123},"two":{"distanceTravelled":234},"three":{"distanceTravelled":345}}}';
		$this->assertSame($expected, $result);

		$carsDto->unserialize($expected);
		$this->assertSame(123, $carsDto->getCars()[0]->getDistanceTravelledOrFail());
		$this->assertSame(234, $carsDto->getCars()[1]->getDistanceTravelledOrFail());
		$this->assertSame(345, $carsDto->getCars()[2]->getDistanceTravelledOrFail());
	}

	/**
	 * @return void
	 */
	public function testUnserialize() {
		$carDto = new CarDto();

		$serializedDto = '{"distanceTravelled":11}';

		$carDto = $carDto->unserialize($serializedDto);
		$this->assertSame(11, $carDto->getDistanceTravelledOrFail());
	}

	/**
	 * @return void
	 */
	public function testDebugInfo() {
		$carDto = new CarDto();

		$result = $carDto->__debugInfo();
		$this->assertSame(['data', 'touched', 'extends', 'immutable'], array_keys($result));
	}

	/**
	 * @return void
	 */
	public function testField() {
		$carDto = new CarDto();

		$field = $carDto->field('distance_travelled', $carDto::TYPE_UNDERSCORED);
		$this->assertSame('distanceTravelled', $field);

		$field = $carDto->field('distance-travelled', $carDto::TYPE_DASHED);
		$this->assertSame('distanceTravelled', $field);
	}

	/**
	 * @return void
	 */
	public function testSetGetHas() {
		$carDto = new CarDto();

		$this->assertFalse($carDto->has($carDto::FIELD_DISTANCE_TRAVELLED));

		$carDto->set($carDto::FIELD_DISTANCE_TRAVELLED, 11);
		$this->assertSame(11, $carDto->get($carDto::FIELD_DISTANCE_TRAVELLED));

		$this->assertTrue($carDto->has($carDto::FIELD_DISTANCE_TRAVELLED));
	}

	/**
	 * @return void
	 */
	public function testPropertyAccess() {
		$carDto = new CarDto();

		$this->assertNull($carDto->distanceTravelled);

		$this->assertFalse(isset($carDto->distanceTravelled));
		$this->assertFalse(!empty($carDto->distanceTravelled));

		$carDto->setDistanceTravelled(111);

		$this->assertTrue(isset($carDto->distanceTravelled));
		$this->assertTrue(!empty($carDto->distanceTravelled));

		$result = $carDto->distanceTravelled;
		$this->assertSame(111, $result);
	}

	/**
	 * @return void
	 */
	public function testPropertyAccessWrite() {
		$carDto = new CarDto();

		$carDto->distanceTravelled = 111;

		$result = $carDto->distanceTravelled;
		$this->assertSame(111, $result);
	}

	/**
	 * @return void
	 */
	public function testPropertyAccessWriteInvalid() {
		$carDto = new CarDto();

		$this->expectException(RuntimeException::class);

		$carDto->distance_travelled = 111;
	}

	/**
	 * isset() apparently is also true for empty (null value).
	 *
	 * @return void
	 */
	public function testDefaultValue() {
		$flyingCarDto = new FlyingCarDto();

		$result = $flyingCarDto->getMaxAltitude();
		$this->assertSame(0, $result);

		$result = $flyingCarDto->getMaxSpeed();
		$this->assertSame(0, $result);

		$this->assertSame([], $flyingCarDto->touchedFields());

		$flyingCarDto->setMaxSpeed(111);
		$this->assertSame([FlyingCarDto::FIELD_MAX_SPEED], $flyingCarDto->touchedFields());

		$result = $flyingCarDto->getMaxSpeed();
		$this->assertSame(111, $result);
	}

}
