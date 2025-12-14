<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Engine;

use Cake\TestSuite\TestCase;
use PhpCollective\Dto\Config\Dto;
use PhpCollective\Dto\Config\Field;
use PhpCollective\Dto\Config\Schema;
use PhpCollective\Dto\Engine\PhpEngine;
use RuntimeException;

class PhpEngineTest extends TestCase {

	protected PhpEngine $engine;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->engine = new PhpEngine();
	}

	/**
	 * @return void
	 */
	public function testParseFile(): void {
		$examplePhp = ROOT . DS . 'tests/files/php/basic.dto.php';

		$result = $this->engine->parseFile($examplePhp);

		$this->assertArrayHasKey('Car', $result);
		$this->assertArrayHasKey('Cars', $result);
		$this->assertArrayHasKey('Owner', $result);
		$this->assertArrayHasKey('FlyingCar', $result);
		$this->assertArrayHasKey('OldOne', $result);

		// Check Car fields
		$this->assertSame('Car', $result['Car']['name']);
		$this->assertArrayHasKey('color', $result['Car']['fields']);
		$this->assertArrayHasKey('isNew', $result['Car']['fields']);
		$this->assertArrayHasKey('distanceTravelled', $result['Car']['fields']);

		// Check field types
		$this->assertSame('\TestApp\ValueObject\Paint', $result['Car']['fields']['color']['type']);
		$this->assertSame('bool', $result['Car']['fields']['isNew']['type']);
		$this->assertSame('int', $result['Car']['fields']['distanceTravelled']['type']);
		$this->assertSame('string[]', $result['Car']['fields']['attributes']['type']);

		// Check Cars collection
		$this->assertSame('Car[]', $result['Cars']['fields']['cars']['type']);
		$this->assertTrue($result['Cars']['fields']['cars']['collection']);
		$this->assertTrue($result['Cars']['fields']['cars']['associative']);

		// Check FlyingCar extends
		$this->assertSame('Car', $result['FlyingCar']['extends']);
		$this->assertSame(0, $result['FlyingCar']['fields']['maxAltitude']['defaultValue']);
		$this->assertTrue($result['FlyingCar']['fields']['maxSpeed']['required']);

		// Check deprecated
		$this->assertSame('Yeah, sry', $result['OldOne']['deprecated']);
	}

	/**
	 * @return void
	 */
	public function testParseFileImmutable(): void {
		$examplePhp = ROOT . DS . 'tests/files/php/immutable.dto.php';

		$result = $this->engine->parseFile($examplePhp);

		$this->assertArrayHasKey('Transaction', $result);
		$this->assertArrayHasKey('CustomerAccount', $result);

		// Check immutable flag
		$this->assertTrue($result['Transaction']['immutable']);

		// Check required fields
		$this->assertTrue($result['Transaction']['fields']['customerAccount']['required']);
		$this->assertTrue($result['Transaction']['fields']['value']['required']);
		$this->assertTrue($result['Transaction']['fields']['created']['required']);
		$this->assertArrayNotHasKey('required', $result['Transaction']['fields']['comment']);

		// Check CustomerAccount
		$this->assertTrue($result['CustomerAccount']['fields']['customerName']['required']);
	}

	/**
	 * @return void
	 */
	public function testParseThrowsException(): void {
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('PhpEngine does not support parsing string content');

		$this->engine->parse('<?php return [];');
	}

	/**
	 * @return void
	 */
	public function testExtension(): void {
		$this->assertSame('php', $this->engine->extension());
	}

	/**
	 * @return void
	 */
	public function testSchemaBuilder(): void {
		$schema = Schema::create()
			->dto(Dto::create('User')->fields(
				Field::int('id')->required(),
				Field::string('email')->required(),
				Field::string('name'),
			))
			->toArray();

		$this->assertArrayHasKey('User', $schema);
		$this->assertSame('int', $schema['User']['fields']['id']['type']);
		$this->assertTrue($schema['User']['fields']['id']['required']);
		$this->assertSame('string', $schema['User']['fields']['email']['type']);
		$this->assertSame('string', $schema['User']['fields']['name']);
	}

	/**
	 * @return void
	 */
	public function testDtoBuilder(): void {
		$dto = Dto::immutable('Order')
			->extends('BaseOrder')
			->deprecated('Use NewOrder instead')
			->fields(
				Field::int('id')->required(),
				Field::float('total'),
			)
			->toArray();

		$this->assertTrue($dto['immutable']);
		$this->assertSame('BaseOrder', $dto['extends']);
		$this->assertSame('Use NewOrder instead', $dto['deprecated']);
		$this->assertArrayHasKey('id', $dto['fields']);
		$this->assertArrayHasKey('total', $dto['fields']);
	}

	/**
	 * @return void
	 */
	public function testFieldBuilderTypes(): void {
		// Test all field type builders
		$this->assertSame('string', Field::string('test')->toArray());
		$this->assertSame('int', Field::int('test')->toArray());
		$this->assertSame('float', Field::float('test')->toArray());
		$this->assertSame('bool', Field::bool('test')->toArray());
		$this->assertSame('mixed', Field::mixed('test')->toArray());
		$this->assertSame('string[]', Field::array('test', 'string')->toArray());
		$this->assertSame('array', Field::array('test')->toArray());
		$this->assertSame('Item[]', Field::collection('items', 'Item')->toArray()['type']);
		$this->assertSame('\DateTime', Field::class('date', 'DateTime')->toArray());
		$this->assertSame('\App\Enum\Status', Field::enum('status', 'App\Enum\Status')->toArray());
		$this->assertSame('int|string', Field::union('id', 'int', 'string')->toArray());
		$this->assertSame('custom', Field::of('test', 'custom')->toArray());
	}

	/**
	 * @return void
	 */
	public function testFieldBuilderModifiers(): void {
		$field = Field::string('email')
			->required()
			->default('test@example.com')
			->deprecated('Use newEmail')
			->factory('createFromString')
			->serialize('string')
			->toArray();

		$this->assertSame('string', $field['type']);
		$this->assertTrue($field['required']);
		$this->assertSame('test@example.com', $field['defaultValue']);
		$this->assertSame('Use newEmail', $field['deprecated']);
		$this->assertSame('createFromString', $field['factory']);
		$this->assertSame('string', $field['serialize']);
	}

	/**
	 * @return void
	 */
	public function testCollectionFieldModifiers(): void {
		$field = Field::collection('items', 'Item')
			->singular('item')
			->associative('id')
			->asCollection('\Cake\Collection\Collection')
			->toArray();

		$this->assertSame('Item[]', $field['type']);
		$this->assertTrue($field['collection']);
		$this->assertSame('item', $field['singular']);
		$this->assertTrue($field['associative']);
		$this->assertSame('id', $field['key']);
		$this->assertSame('\Cake\Collection\Collection', $field['collectionType']);
	}

}
