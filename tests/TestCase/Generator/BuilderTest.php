<?php

namespace CakeDto\Test\TestCase\Generator;

use CakeDto\Engine\EngineInterface;
use CakeDto\Engine\XmlEngine;
use CakeDto\Generator\Builder;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
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

		Configure::write('CakeDto.scalarTypeHints', false);

		$engine = new XmlEngine();
		$this->builder = new Builder($engine);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->builder);

		Configure::write('CakeDto.scalarTypeHints', false);
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
		$this->assertSame('\Cake\I18n\FrozenDate', $result['Car']['fields']['manufactured']['type']);

		$this->assertFalse($result['Car']['fields']['attributes']['collection']);
		$this->assertSame('array', $result['Car']['fields']['attributes']['typeHint']);

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
			'isArray' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'array',
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['simpleAttributes']);

		$expected = [
			'associative' => false,
			'name' => 'attributes',
			'type' => 'string[]',
			'required' => false,
			'defaultValue' => null,
			'nullable' => true,
			'isArray' => true,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => 'array',
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['attributes']);

		$expected = [
			'associative' => false,
			'name' => 'collectionAttributes',
			'type' => 'string[]|\ArrayObject',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'isArray' => false,
			'dto' => null,
			'collection' => true,
			'key' => null,
			'collectionType' => '\ArrayObject',
			'singular' => 'collectionAttribute',
			'singularType' => 'string',
			'singularTypeHint' => null,
			'singularReturnTypeHint' => null,
			'typeHint' => '\ArrayObject',
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,

		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['collectionAttributes']);

		$expected = [
			'name' => 'arrayAttributes',
			'type' => 'string[]',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'collectionType' => 'array',
			'associative' => true,
			'isArray' => false,
			'dto' => null,
			'collection' => true,
			'key' => null,
			'singular' => 'arrayAttribute',
			'singularType' => 'string',
			'singularTypeHint' => null,
			'singularReturnTypeHint' => null,
			'typeHint' => 'array',
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,

		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['arrayAttributes']);

		$expected = [
			'name' => 'customCollectionAttributes',
			'type' => 'string[]|\Cake\Collection\Collection',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'collectionType' => '\Cake\Collection\Collection',
			'isArray' => false,
			'dto' => null,
			'associative' => false,
			'collection' => true,
			'key' => null,
			'singularType' => 'string',
			'singularTypeHint' => null,
			'singularReturnTypeHint' => null,
			'singular' => 'customCollectionAttribute',
			'typeHint' => '\Cake\Collection\Collection',
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['customCollectionAttributes']);

		$expected = [
			'name' => 'myPlural',
			'type' => 'string[]|\ArrayObject',
			'required' => false,
			'defaultValue' => null,
			'nullable' => false,
			'collectionType' => '\ArrayObject',
			'isArray' => false,
			'dto' => null,
			'associative' => false,
			'collection' => true,
			'key' => null,
			'singularType' => 'string',
			'singularTypeHint' => null,
			'singularReturnTypeHint' => null,
			'singular' => 'mySingular',
			'typeHint' => '\ArrayObject',
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['autoCollectionBySingular']);
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
			'isArray' => false,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => null,
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['unionScalarField']);

		$expected = [
			'associative' => false,
			'name' => 'unionArrayField',
			'type' => 'string[]|int[]',
			'defaultValue' => null,
			'required' => false,
			'nullable' => true,
			'isArray' => false,
			'dto' => null,
			'collection' => false,
			'collectionType' => null,
			'key' => null,
			'typeHint' => null,
			'deprecated' => null,
			'serializable' => false,
			'toArray' => false,
		];
		$this->assertAssociativeArraySame($expected, $result['Demo']['fields']['unionArrayField']);
	}

	/**
	 * @return \CakeDto\Generator\Builder|\PHPUnit\Framework\MockObject\MockObject
	 */
	protected function createBuilder() {
		$engine = $this->getMockBuilder(EngineInterface::class)->getMock();
		$builder = $this->getMockBuilder(Builder::class)->setMethods(['_merge', '_getFiles'])->setConstructorArgs([$engine])->getMock();
		$builder->expects($this->any())->method('_getFiles')->willReturn([]);

		return $builder;
	}

}
