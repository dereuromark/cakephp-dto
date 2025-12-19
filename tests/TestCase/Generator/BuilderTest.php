<?php

namespace CakeDto\Test\TestCase\Generator;

use Cake\TestSuite\TestCase;
use CakeDto\Filesystem\Folder;
use CakeDto\Generator\Builder;
use InvalidArgumentException;
use PhpCollective\Dto\Engine\EngineInterface;
use PhpCollective\Dto\Engine\XmlEngine;
use TestApp\Dto\AuthorDto;
use TestApp\Dto\CarDto;
use TestApp\DtoCustom\DummyNonDtoClass;
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

		$this->assertSame('CarDto', $result['Car']['className']);
		$this->assertSame('CarsDto', $result['Cars']['className']);
		$this->assertSame('OwnerDto', $result['Owner']['className']);
		$this->assertSame('FlyingCarDto', $result['FlyingCar']['className']);
		$this->assertSame('OldOneDto', $result['OldOne']['className']);
		$this->assertSame('EmptyOneDto', $result['EmptyOne']['className']);

		$this->assertSame('Owner', $result['Car']['fields']['owner']['dto']);
		$this->assertSame('\App\Dto\OwnerDto', $result['Car']['fields']['owner']['typeHint']);
		$this->assertSame('?\App\Dto\OwnerDto', $result['Car']['fields']['owner']['nullableTypeHint']);
		$this->assertSame('\Cake\I18n\Date', $result['Car']['fields']['manufactured']['type']);

		$this->assertFalse($result['Car']['fields']['attributes']['collection']);
		$this->assertSame('array', $result['Car']['fields']['attributes']['typeHint']);
		$this->assertSame('?array', $result['Car']['fields']['attributes']['nullableTypeHint']);

		$this->assertTrue($result['Cars']['fields']['cars']['collection']);
		$this->assertSame('\ArrayObject', $result['Cars']['fields']['cars']['typeHint']);
	}

	/**
	 * @return void
	 */
	public function testBuildWithoutSuffix() {
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
		$this->builder->setConfig('suffix', '');
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

		$this->assertSame('Car', $result['Car']['className']);
		$this->assertSame('Cars', $result['Cars']['className']);
		$this->assertSame('Owner', $result['Owner']['className']);
		$this->assertSame('FlyingCar', $result['FlyingCar']['className']);
		$this->assertSame('OldOne', $result['OldOne']['className']);
		$this->assertSame('EmptyOne', $result['EmptyOne']['className']);

		$this->assertSame('\App\Dto\Owner', $result['Car']['fields']['owner']['typeHint']);
		$this->assertSame('?\App\Dto\Owner', $result['Car']['fields']['owner']['nullableTypeHint']);
	}

	/**
	 * @return void
	 */
	public function testBuildWithCustomSuffix() {
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
		$this->builder->setConfig('suffix', 'Data');
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

		$this->assertSame('CarData', $result['Car']['className']);
		$this->assertSame('CarsData', $result['Cars']['className']);
		$this->assertSame('OwnerData', $result['Owner']['className']);
		$this->assertSame('FlyingCarData', $result['FlyingCar']['className']);
		$this->assertSame('OldOneData', $result['OldOne']['className']);
		$this->assertSame('EmptyOneData', $result['EmptyOne']['className']);

		$this->assertSame('\App\Dto\OwnerData', $result['Car']['fields']['owner']['typeHint']);
		$this->assertSame('?\App\Dto\OwnerData', $result['Car']['fields']['owner']['nullableTypeHint']);
	}

	/**
	 * @return void
	 */
	public function testBuildCollectionSingular() {
		$this->builder = $this->createBuilder();

		$result = [
			'Demo' => [
				'name' => 'Demo',
				'fields' => [
					'parentCategories' => [
						'name' => 'parentCategories',
						'type' => 'CodeDescription[]',
						'collection' => true,
					],
					'subCategories' => [
						'name' => 'subCategories',
						'type' => 'FilterElement[]',
						'collection' => true,
					],
					'brands' => [
						'name' => 'brands',
						'type' => 'FilterElement[]',
						'collection' => true,
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$expected = [
			'parentCategories' => [
				'name' => 'parentCategories',
				'type' => '\App\Dto\CodeDescriptionDto[]|\ArrayObject',
				'collection' => true,
				'required' => false,
				'defaultValue' => null,
				'nullable' => false,
				'returnTypeHint' => '\ArrayObject',
				'nullableTypeHint' => null,
				'isArray' => false,
				'dto' => null,
				'collectionType' => '\ArrayObject',
				'associative' => false,
				'key' => null,
				'deprecated' => null,
				'serialize' => null,
				'factory' => null,
				'mapFrom' => null,
				'mapTo' => null,
				'singularType' => '\App\Dto\CodeDescriptionDto',
				'singularClass' => '\App\Dto\CodeDescriptionDto',
				'singular' => 'parentCategory',
				'singularNullable' => false,
				'typeHint' => '\ArrayObject',
				'docBlockType' => '\ArrayObject<int, \App\Dto\CodeDescriptionDto>',
				'singularTypeHint' => '\App\Dto\CodeDescriptionDto',
				'singularReturnTypeHint' => '\App\Dto\CodeDescriptionDto',
				'singularNullableReturnTypeHint' => null,
				'keyType' => 'int',
			],
			'subCategories' => [
				'name' => 'subCategories',
				'type' => '\App\Dto\FilterElementDto[]|\ArrayObject',
				'collection' => true,
				'required' => false,
				'defaultValue' => null,
				'nullable' => false,
				'returnTypeHint' => '\ArrayObject',
				'nullableTypeHint' => null,
				'isArray' => false,
				'dto' => null,
				'collectionType' => '\ArrayObject',
				'associative' => false,
				'key' => null,
				'deprecated' => null,
				'serialize' => null,
				'factory' => null,
				'mapFrom' => null,
				'mapTo' => null,
				'singularType' => '\App\Dto\FilterElementDto',
				'singularClass' => '\App\Dto\FilterElementDto',
				'singular' => 'subCategory',
				'singularNullable' => false,
				'typeHint' => '\ArrayObject',
				'docBlockType' => '\ArrayObject<int, \App\Dto\FilterElementDto>',
				'singularTypeHint' => '\App\Dto\FilterElementDto',
				'singularReturnTypeHint' => '\App\Dto\FilterElementDto',
				'singularNullableReturnTypeHint' => null,
				'keyType' => 'int',
			],
			'brands' => [
				'name' => 'brands',
				'type' => '\App\Dto\FilterElementDto[]|\ArrayObject',
				'collection' => true,
				'required' => false,
				'defaultValue' => null,
				'nullable' => false,
				'returnTypeHint' => '\ArrayObject',
				'nullableTypeHint' => null,
				'isArray' => false,
				'dto' => null,
				'collectionType' => '\ArrayObject',
				'associative' => false,
				'key' => null,
				'deprecated' => null,
				'serialize' => null,
				'factory' => null,
				'mapFrom' => null,
				'mapTo' => null,
				'singularType' => '\App\Dto\FilterElementDto',
				'singularClass' => '\App\Dto\FilterElementDto',
				'singular' => 'brand',
				'singularNullable' => false,
				'typeHint' => '\ArrayObject',
				'docBlockType' => '\ArrayObject<int, \App\Dto\FilterElementDto>',
				'singularTypeHint' => '\App\Dto\FilterElementDto',
				'singularReturnTypeHint' => '\App\Dto\FilterElementDto',
				'singularNullableReturnTypeHint' => null,
				'keyType' => 'int',
			],
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']);
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
			'nullableReturnTypeHint' => '?array',
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
			'mapFrom' => null,
			'mapTo' => null,
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
			'nullableReturnTypeHint' => '?array',
			'returnTypeHint' => 'array',
			'isArray' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'array',
			'docBlockType' => 'array<int, string>',
			'deprecated' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'singularNullableReturnTypeHint' => null,
			'typeHint' => '\ArrayObject',
			'docBlockType' => '\ArrayObject<int, string>',
			'deprecated' => null,
			'returnTypeHint' => '\ArrayObject',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'keyType' => 'int',
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
			'singularNullableReturnTypeHint' => null,
			'typeHint' => 'array',
			'docBlockType' => 'array<string, string>',
			'deprecated' => null,
			'returnTypeHint' => 'array',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'keyType' => 'string',
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
			'singularNullableReturnTypeHint' => null,
			'singular' => 'customCollectionAttribute',
			'typeHint' => '\Cake\Collection\Collection',
			'docBlockType' => '\Cake\Collection\Collection<int, string>',
			'deprecated' => null,
			'returnTypeHint' => '\Cake\Collection\Collection',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'keyType' => 'int',
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
			'singularNullableReturnTypeHint' => null,
			'singular' => 'mySingular',
			'typeHint' => '\ArrayObject',
			'docBlockType' => '\ArrayObject<int, string>',
			'deprecated' => null,
			'returnTypeHint' => '\ArrayObject',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'keyType' => 'int',
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
			'singularNullableReturnTypeHint' => '?string',
			'singular' => 'mySingularNullable',
			'typeHint' => '\ArrayObject',
			'docBlockType' => '\ArrayObject<int, string|null>',
			'deprecated' => null,
			'returnTypeHint' => '\ArrayObject',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'keyType' => 'int',
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
		$this->expectExceptionMessage("Invalid field name 'foo_bar' in 'FlyingCar' DTO.");

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
				'fields' => [],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Invalid 'extends' attribute for 'FlyingCar' DTO: class 'C?r' does not exist.");

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
				'fields' => [],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Invalid 'extends' attribute for 'FlyingCar' DTO: class 'Car' does not exist.");

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testBuildExtendsOtherClass() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => CarDto::class,
				'fields' => [],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);
		$this->assertSame(CarDto::class, $result['FlyingCar']['extends']);
		$this->assertFalse($result['FlyingCar']['immutable']);
	}

	/**
	 * @return void
	 */
	public function testBuildExtendsInvalidNonDtoClass() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => DummyNonDtoClass::class,
				'fields' => [],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Invalid 'extends' attribute for 'FlyingCar' DTO: 'TestApp\DtoCustom\DummyNonDtoClass' must extend PhpCollective\Dto\Dto\AbstractDto.");

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testBuildExtendsInvalidOtherImmutableClass() {
		$this->builder = $this->createBuilder();

		$result = [
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => AuthorDto::class,
				'fields' => [],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Invalid 'extends' attribute for 'FlyingCar' DTO: 'TestApp\Dto\AuthorDto' is immutable.");

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
		$this->expectExceptionMessage("Invalid collection type 'Wheel' for field 'wheels' in 'FlyingCar' DTO.");

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

		// Union types now generate native PHP type hints (PHP 8.0+)
		$expected = [
			'associative' => false,
			'name' => 'unionScalarField',
			'type' => 'string|float|int',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => 'string|float|int|null',
			'nullableReturnTypeHint' => 'string|float|int|null',
			'isArray' => false,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'string|float|int',
			'deprecated' => null,
			'returnTypeHint' => 'string|float|int',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['unionScalarField']);

		// Array union types get 'array' as native type hint (best runtime type check available)
		// PHPDoc @param string[]|int[] still provides precise type for static analysis
		$expected = [
			'associative' => false,
			'name' => 'unionArrayField',
			'type' => 'string[]|int[]',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'nullableTypeHint' => '?array',
			'nullableReturnTypeHint' => '?array',
			'isArray' => false,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'array',
			'deprecated' => null,
			'returnTypeHint' => 'array',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'nullableReturnTypeHint' => '?\DateTimeImmutable',
			'isArray' => false,
			'isClass' => true,
			'dto' => null,
			'enum' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => '\DateTimeImmutable',
			'deprecated' => null,
			'returnTypeHint' => '\DateTimeImmutable',
			'serialize' => 'string',
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'nullableReturnTypeHint' => '?\TestApp\ValueObject\Paint',
			'isArray' => false,
			'isClass' => true,
			'dto' => null,
			'enum' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => '\TestApp\ValueObject\Paint',
			'deprecated' => null,
			'returnTypeHint' => '\TestApp\ValueObject\Paint',
			'serialize' => 'FromArrayToArray',
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
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
			'nullableReturnTypeHint' => '?\TestApp\ValueObject\Birthday',
			'isArray' => false,
			'isClass' => true,
			'dto' => null,
			'enum' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => '\TestApp\ValueObject\Birthday',
			'deprecated' => null,
			'returnTypeHint' => '\TestApp\ValueObject\Birthday',
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['birthday']);
	}

	/**
	 * @return void
	 */
	public function testBuildSingularCollisionWithExplicitSingular(): void {
		$this->builder = $this->createBuilder();

		$result = [
			'Demo' => [
				'name' => 'Demo',
				'fields' => [
					'persons' => [
						'name' => 'persons',
						'type' => 'string[]',
						'collection' => true,
						'singular' => 'person',
					],
					'person' => [
						'name' => 'person',
						'type' => 'string',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Invalid singular name 'person' for collection field 'persons' in 'Demo' DTO.");

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testBuildSingularCollisionWithAutoGeneratedSingular(): void {
		$this->builder = $this->createBuilder();

		$result = [
			'Demo' => [
				'name' => 'Demo',
				'fields' => [
					'items' => [
						'name' => 'items',
						'type' => 'string[]',
						'collection' => true,
					],
					'item' => [
						'name' => 'item',
						'type' => 'string',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Auto-generated singular 'item' for collection field 'items' in 'Demo' DTO collides with existing field.");

		$this->builder->build(TMP);
	}

	/**
	 * @return void
	 */
	public function testFieldMapping(): void {
		$this->builder = $this->createBuilder();

		$result = [
			'User' => [
				'name' => 'User',
				'fields' => [
					'emailAddress' => [
						'name' => 'emailAddress',
						'type' => 'string',
						'mapFrom' => 'email',
						'mapTo' => 'email_address',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$this->assertSame('email', $result['User']['fields']['emailAddress']['mapFrom']);
		$this->assertSame('email_address', $result['User']['fields']['emailAddress']['mapTo']);
	}

	/**
	 * @return void
	 */
	public function testTraits(): void {
		$this->builder = $this->createBuilder();

		$result = [
			'Article' => [
				'name' => 'Article',
				'traits' => ['\App\Dto\Traits\TimestampTrait', '\App\Dto\Traits\SlugTrait'],
				'fields' => [
					'title' => [
						'name' => 'title',
						'type' => 'string',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$this->assertSame(['\App\Dto\Traits\TimestampTrait', '\App\Dto\Traits\SlugTrait'], $result['Article']['traits']);
	}

	/**
	 * @return void
	 */
	public function testSerializeMode(): void {
		$this->builder = $this->createBuilder();

		$result = [
			'User' => [
				'name' => 'User',
				'fields' => [
					'password' => [
						'name' => 'password',
						'type' => 'string',
						'serialize' => 'hidden',
					],
				],
			],
		];
		$this->builder->expects($this->any())->method('_merge')->willReturn($result);

		$result = $this->builder->build(TMP);

		$this->assertSame('hidden', $result['User']['fields']['password']['serialize']);
	}

	/**
	 * @return \CakeDto\Generator\Builder|\PHPUnit\Framework\MockObject\MockObject
	 */
	protected function createBuilder(): Builder {
		$engine = $this->getMockBuilder(EngineInterface::class)->getMock();
		$builder = $this->getMockBuilder(Builder::class)->onlyMethods(['_merge', '_getFiles'])->setConstructorArgs([$engine])->getMock();
		$builder->expects($this->any())->method('_getFiles')->willReturn([]);

		return $builder;
	}

}
