<?php

namespace CakeDto\Test\TestCase\Engine;

use CakeDto\Engine\YamlEngine;
use Cake\TestSuite\TestCase;
use TestApp\TestSuite\AssociativeArrayTestTrait;

class YamlEngineTest extends TestCase {

	use AssociativeArrayTestTrait;

	/**
	 * @var \CakeDto\Engine\EngineInterface
	 */
	protected $engine;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->engine = new YamlEngine();

		$this->skipIf(!function_exists('yaml_parse'), 'ext-yaml needed for this test');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->engine);
	}

	/**
	 * @return void
	 */
	public function testParse() {
		$exampleYml = ROOT . DS . 'docs/examples/basic.dto.yml';
		$content = file_get_contents($exampleYml);

		$result = $this->engine->parse($content);
		$expected = [
			'Car' => [
				'fields' => [
					'color' => [
						'name' => 'color',
						'type' => '\TestApp\ValueObject\Paint',
					],
					'isNew' => [
						'name' => 'isNew',
						'type' => 'bool'
					],
					'value' => [
						'name' => 'value',
						'type' => 'float'
					],
					'distanceTravelled' => [
						'name' => 'distanceTravelled',
						'type' => 'int'
					],
					'attributes' => [
						'name' => 'attributes',
						'type' => 'string[]',
					],
					'manufactured' => [
						'name' => 'manufactured',
						'type' => '\Cake\I18n\FrozenDate'
					],
					'owner' => [
						'name' => 'owner',
						'type' => 'Owner'
					],
					'service' => [
						'name' => 'service',
						'type' => 'string[]|int|float'
					]
				],
				'name' => 'Car',
			],
			'Cars' => [

				'fields' => [
					'cars' => [
						'name' => 'cars',
						'type' => 'Car[]',
						'collection' => true,
					]
				],
				'name' => 'Cars',
			],
			'Owner' => [
				'fields' => [
					'name' => [
						'name' => 'name',
						'type' => 'string'
					],
					'birthYear' => [
						'name' => 'birthYear',
						'type' => 'int'
					]
				],
				'name' => 'Owner',
			],
			'FlyingCar' => [
				'name' => 'FlyingCar',
				'extends' => 'Car',
				'fields' => [
					'maxAltitude' => [
						'name' => 'maxAltitude',
						'type' => 'int',
						'defaultValue' => 0,
					],
					'maxSpeed' => [
						'name' => 'maxSpeed',
						'type' => 'int',
						'defaultValue' => 0,
						'required' => true,
					],
					'complexAttributes' => [
						'name' => 'complexAttributes',
						'type' => 'array'
					],
				]
			],
			'OldOne' => [
				'name' => 'OldOne',
				'deprecated' => 'Yeah, sry',
				'fields' => [
					'name' => [
						'name' => 'name',
						'type' => 'string'
					]
				]
			]
		];

		$this->assertAssociativeArray($expected, $result);
	}

}
