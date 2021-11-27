<?php

namespace CakeDto\Test\TestCase\Generator;

use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
use CakeDto\Engine\EngineInterface;
use CakeDto\Engine\XmlEngine;
use CakeDto\Generator\Builder;
use InvalidArgumentException;
use TestApp\TestSuite\AssociativeArrayTestTrait;

class BuilderTest extends TestCase {

	use AssociativeArrayTestTrait;

	/**
	 * @var \CakeDto\Generator\Builder
	 */
	protected $builder;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$engine = new XmlEngine();
		$this->builder = new Builder($engine);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->builder);
	}

	/**
	 * @return void
	 */
	public function testBuild() {
		$configPath = TMP . 'config' . DS;
		if (!is_dir($configPath)) {
			mkdir($configPath, 0700, true);
		}
		$srcPath = TMP . 'src' . DS;
		$folder = new Folder($srcPath);
		$folder->delete();
		if (!is_dir($srcPath)) {
			mkdir($srcPath, 0700, true);
		}

		$exampleXml = ROOT . DS . 'docs/examples/basic.dto.xml';
		copy($exampleXml, $configPath . 'dto.xml');

		$options = [];
		$result = $this->builder->build($configPath, $options);

		$expected = [
			'Car',
			'Cars',
			'Owner',
			'FlyingCar',
			'OldOne',
			'EmptyOne',
		];
		$this->assertSame($expected, array_keys($result));

		$this->assertSame('Owner', $result['Car']['fields']['owner']['dto']);
		$this->assertSame('\App\Dto\OwnerDto', $result['Car']['fields']['owner']['typeHint']);
		$this->assertSame('?\App\Dto\OwnerDto', $result['Car']['fields']['owner']['nullableTypeHint']);
		$this->assertSame('\Cake\I18n\FrozenDate', $result['Car']['fields']['manufactured']['type']);

		$this->assertFalse($result['Car']['fields']['attributes']['collection']);
		$this->assertSame('array', $result['Car']['fields']['attributes']['typeHint']);
		$this->assertSame('?array', $result['Car']['fields']['attributes']['nullableTypeHint']);

		$this->assertTrue($result['Cars']['fields']['cars']['collection']);
		$this->assertSame('\ArrayObject', $result['Cars']['fields']['cars']['typeHint']);
	}

	/**
	 * @return void
	 */
	public function testBuildCollections() {
		$this->builder = $this->createBuilder();

		$result = [
			'Demo' => [
				'name' => 'Demo',
				'fields' => [
					'simpleAttributes' => [
						'name' => 'simpleAttributes',
						'type' => 'array',
					],
					'attributes' => [
						'name' => 'attributes',
						'type' => 'string[]',
					],
					'arrayAttributes' => [
						'name' => 'arrayAttributes',
						'type' => 'string[]',
						'collectionType' => 'array',
						'associative' => true,
					],
					'collectionAttributes' => [
						'name' => 'collectionAttributes',
						'type' => 'string[]',
						'collection' => true,
					],
					'customCollectionAttributes' => [
						'name' => 'customCollectionAttributes',
						'type' => 'string[]',
						'collectionType' => '\Cake\Collection\Collection',
					],
					'autoCollectionBySingular' => [
						'name' => 'myPlural',
						'type' => 'string[]',
						'singular' => 'mySingular',
					],
					'autoCollectionBySingularNullable' => [
						'name' => 'myPluralNullable',
						'type' => '?string[]',
						'singular' => 'mySingularNullable',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$expected = [
			'associative' => false,
			'name' => 'simpleAttributes',
			'type' => 'array',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => '?array',
			'isArray' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'array',
			'deprecated' => null,
			'returnTypeHint' => 'array',
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['simpleAttributes']);

		$expected = [
			'associative' => false,
			'name' => 'attributes',
			'type' => 'string[]',
			'required' => false,
			'defaultValue' => null,
			'nullable' => true,
			'nullableTypeHint' => '?array',
			'returnTypeHint' => 'array',
			'isArray' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'array',
			'deprecated' => null,
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['attributes']);

		$expected = [
			'associative' => false,
			'name' => 'collectionAttributes',
			'type' => 'string[]|\ArrayObject',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'nullableTypeHint' => null,
			'isArray' => false,
			'dto' => null,
			'collection' => true,
			'key' => null,
			'collectionType' => '\ArrayObject',
			'singular' => 'collectionAttribute',
			'singularType' => 'string',
			'singularTypeHint' => 'string',
			'singularNullable' => false,
			'singularReturnTypeHint' => 'string',
			'typeHint' => '\ArrayObject',
			'deprecated' => null,
			'returnTypeHint' => '\ArrayObject',
			'serialize' => null,
			'factory' => null,

		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['collectionAttributes']);

		$expected = [
			'name' => 'arrayAttributes',
			'type' => 'string[]',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'nullableTypeHint' => null,
			'collectionType' => 'array',
			'associative' => true,
			'isArray' => false,
			'dto' => null,
			'collection' => true,
			'key' => null,
			'singular' => 'arrayAttribute',
			'singularType' => 'string',
			'singularTypeHint' => 'string',
			'singularNullable' => false,
			'singularReturnTypeHint' => 'string',
			'typeHint' => 'array',
			'deprecated' => null,
			'returnTypeHint' => 'array',
			'serialize' => null,
			'factory' => null,

		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['arrayAttributes']);

		$expected = [
			'name' => 'customCollectionAttributes',
			'type' => 'string[]|\Cake\Collection\Collection',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'nullableTypeHint' => null,
			'collectionType' => '\Cake\Collection\Collection',
			'isArray' => false,
			'dto' => null,
			'associative' => false,
			'collection' => true,
			'key' => null,
			'singularType' => 'string',
			'singularTypeHint' => 'string',
			'singularNullable' => false,
			'singularReturnTypeHint' => 'string',
			'singular' => 'customCollectionAttribute',
			'typeHint' => '\Cake\Collection\Collection',
			'deprecated' => null,
			'returnTypeHint' => '\Cake\Collection\Collection',
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['customCollectionAttributes']);

		$expected = [
			'name' => 'myPlural',
			'type' => 'string[]|\ArrayObject',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'nullableTypeHint' => null,
			'collectionType' => '\ArrayObject',
			'isArray' => false,
			'dto' => null,
			'associative' => false,
			'collection' => true,
			'key' => null,
			'singularType' => 'string',
			'singularTypeHint' => 'string',
			'singularNullable' => false,
			'singularReturnTypeHint' => 'string',
			'singular' => 'mySingular',
			'typeHint' => '\ArrayObject',
			'deprecated' => null,
			'returnTypeHint' => '\ArrayObject',
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['autoCollectionBySingular']);

		$expected = [
			'name' => 'myPluralNullable',
			'type' => '(string|null)[]|\ArrayObject',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'nullableTypeHint' => null,
			'collectionType' => '\ArrayObject',
			'isArray' => false,
			'dto' => null,
			'associative' => false,
			'collection' => true,
			'key' => null,
			'singularType' => 'string',
			'singularTypeHint' => 'string',
			'singularNullable' => true,
			'singularReturnTypeHint' => 'string',
			'singular' => 'mySingularNullable',
			'typeHint' => '\ArrayObject',
			'deprecated' => null,
			'returnTypeHint' => '\ArrayObject',
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['autoCollectionBySingularNullable']);
	}

	/**
	 * @return void
	 */
	public function testBuildInvalidName() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'fields' => [
					'foo_bar' => [
						'name' => 'foo_bar',
						'type' => 'string',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid field attribute `name` in foo_bar DTO: `foo_bar`.');

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testBuildInvalidExtends() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => 'C?r',
				'fields' => [
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid FlyingCar DTO attribute `extends`: `C?r`. Only DTOs are allowed.');

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testBuildNonExistentExtends() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => 'Car',
				'fields' => [
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid FlyingCar DTO attribute `extends`: `Car`. DTO does not seem to exist.');

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testBuildNonValidCollection() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => 'Car',
				'fields' => [
					'wheels' => [
						'name' => 'wheels',
						'type' => 'Wheel',
						'collection' => true,
					],
				],
			],
			'Wheel' => [
				'name' => 'Wheel',
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid field type `Wheel` in FlyingCar DTO, expected a collection `...[]`');

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testUnionOfSimpleType() {
		$this->builder = $this->createBuilder();

		$result = [
			'Demo' => [
				'name' => 'Demo',
				'fields' => [
					'unionScalarField' => [
						'name' => 'unionScalarField',
						'type' => 'string|float|int',
					],
					'unionArrayField' => [
						'name' => 'unionArrayField',
						'type' => 'string[]|int[]',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$expected = [
			'associative' => false,
			'name' => 'unionScalarField',
			'type' => 'string|float|int',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => null,
			'isArray' => false,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => null,
			'deprecated' => null,
			'returnTypeHint' => null,
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['unionScalarField']);

		$expected = [
			'associative' => false,
			'name' => 'unionArrayField',
			'type' => 'string[]|int[]',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => null,
			'isArray' => false,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => null,
			'deprecated' => null,
			'returnTypeHint' => null,
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['unionArrayField']);
	}

	/**
	 * @return void
	 */
	public function testSerializable() {
		$this->builder = $this->createBuilder();

		$result = [
			'Demo' => [
				'name' => 'Demo',
				'fields' => [
					'lastLogin' => [
						'name' => 'lastLogin',
						'type' => '\DateTimeImmutable',
						'serialize' => 'string',
					],
					'color' => [
						'name' => 'color',
						'type' => '\TestApp\ValueObject\Paint',
					],
					'birthday' => [
						'name' => 'birthday',
						'type' => '\TestApp\ValueObject\Birthday',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$expected = [
			'associative' => false,
			'name' => 'lastLogin',
			'type' => '\DateTimeImmutable',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => '?\DateTimeImmutable',
			'isArray' => false,
			'isClass' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => '\DateTimeImmutable',
			'deprecated' => null,
			'returnTypeHint' => '\DateTimeImmutable',
			'serialize' => 'string',
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['lastLogin']);

		$expected = [
			'associative' => false,
			'name' => 'color',
			'type' => '\TestApp\ValueObject\Paint',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => '?\TestApp\ValueObject\Paint',
			'isArray' => false,
			'isClass' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => '\TestApp\ValueObject\Paint',
			'deprecated' => null,
			'returnTypeHint' => '\TestApp\ValueObject\Paint',
			'serialize' => 'FromArrayToArray',
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['color']);

		$expected = [
			'associative' => false,
			'name' => 'birthday',
			'type' => '\TestApp\ValueObject\Birthday',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => '?\TestApp\ValueObject\Birthday',
			'isArray' => false,
			'isClass' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => '\TestApp\ValueObject\Birthday',
			'deprecated' => null,
			'returnTypeHint' => '\TestApp\ValueObject\Birthday',
			'serialize' => null,
			'factory' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['birthday']);
	}

	/**
	 * @return \CakeDto\Generator\Builder|\PHPUnit\Framework\MockObject\MockObject
	 */
	protected function createBuilder() {
		$engine = $this->getMockBuilder(EngineInterface::class)->getMock();
		$builder = $this->getMockBuilder(Builder::class)->onlyMethods(['_merge', '_getFiles'])->setConstructorArgs([$engine])->getMock();
		$builder->expects($this->any())->method('_getFiles')->willReturn([]);

		return $builder;
	}

}
