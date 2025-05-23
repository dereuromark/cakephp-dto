<?php

namespace CakeDto\Test\TestCase\Engine;

use Cake\TestSuite\TestCase;
use CakeDto\Engine\XmlEngine;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

class XmlEngineTest extends TestCase {

	/**
	 * @var \CakeDto\Engine\EngineInterface
	 */
	protected $engine;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->engine = new XmlEngine();
	}

	/**
	 * @return void
	 */
	public function testParse() {
		$exampleXml = ROOT . DS . 'docs/examples/basic.dto.xml';
		$content = file_get_contents($exampleXml);

		$result = $this->engine->parse($content);
		$expected = [
			'Car' => [
				'name' => 'Car',
				'fields' => [
					'color' => [
						'name' => 'color',
						'type' => '\TestApp\ValueObject\Paint',
					],
					'isNew' => [
						'name' => 'isNew',
						'type' => 'bool',
					],
					'value' => [
						'name' => 'value',
						'type' => 'float',
					],
					'distanceTravelled' => [
						'name' => 'distanceTravelled',
						'type' => 'int',
					],
					'attributes' => [
						'name' => 'attributes',
						'type' => 'string[]',
					],
					'manufactured' => [
						'name' => 'manufactured',
						'type' => '\Cake\I18n\Date',
					],
					'owner' => [
						'name' => 'owner',
						'type' => 'Owner',
					],
				],
			],
			'Cars' => [
				'name' => 'Cars',
				'fields' => [
					'cars' => [
						'name' => 'cars',
						'type' => 'Car[]',
						'collection' => true,
						'associative' => true,
					],
				],
			],
			'Owner' => [
				'name' => 'Owner',
				'fields' => [
					'name' => [
						'name' => 'name',
						'type' => 'string',
					],
					'insuranceProvider' => [
						'name' => 'insuranceProvider',
						'type' => 'string',
					],
					'attributes' => [
						'name' => 'attributes',
						'type' => '\TestApp\ValueObject\KeyValuePair',
					],
					'birthday' => [
						'name' => 'birthday',
						'type' => '\TestApp\ValueObject\Birthday',
					],
				],
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
						'type' => 'array',
					],
				],
			],
			'OldOne' => [
				'name' => 'OldOne',
				'extends' => 'Car',
				'deprecated' => 'Yeah, sry',
				'fields' => [
					'name' => [
						'name' => 'name',
						'type' => 'string',
					],
				],
			],
			'EmptyOne' => [
				'name' => 'EmptyOne',
				'fields' => [
				],
			],
		];

		$this->assertSame($expected, $result, (new Differ(new DiffOnlyOutputBuilder()))->diff(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT)));
	}

	/**
	 * @return void
	 */
	public function testParseImmutable() {
		$exampleXml = ROOT . DS . 'docs/examples/immutable.dto.xml';
		$content = file_get_contents($exampleXml);

		$result = $this->engine->parse($content);

		$expected = [
			'Transaction' => [
				'name' => 'Transaction',
				'immutable' => true,
				'fields' => [
					'customerAccount' => [
						'name' => 'customerAccount',
						'type' => 'CustomerAccount',
						'required' => true,
					],
					'value' => [
						'name' => 'value',
						'type' => 'float',
						'required' => true,
					],
					'comment' => [
						'name' => 'comment',
						'type' => 'string',
					],
					'created' => [
						'name' => 'created',
						'type' => '\Cake\I18n\Date',
						'required' => true,
					],
				],
			],
			'CustomerAccount' => [
				'name' => 'CustomerAccount',
				'fields' => [
					'customerName' => [
						'name' => 'customerName',
						'type' => 'string',
						'required' => true,
					],
					'birthYear' => [
						'name' => 'birthYear',
						'type' => 'int',
					],
					'lastLogin' => [
						'name' => 'lastLogin',
						'type' => '\Cake\I18n\DateTime',
					],
				],
			],
		];
		$this->assertSame($expected, $result, print_r($result, true));
	}

	/**
	 * @return void
	 */
	public function testParseDefaultValue() {
		$exampleXml = ROOT . DS . 'tests/files/xml/scalar_default_required_false.xml';
		$content = file_get_contents($exampleXml);

		$result = $this->engine->parse($content);
		$expected = [
			'DefaultValue' => [
				'name' => 'DefaultValue',
				'fields' => [
					'defaultedOptionalField' => [
						'name' => 'defaultedOptionalField',
						'type' => 'int',
						'defaultValue' => 0,
						'required' => false,
					],
					'defaultedBoolField' => [
						'name' => 'defaultedBoolField',
						'type' => 'bool',
						'defaultValue' => true,
					],
					'defaultedStringField' => [
						'name' => 'defaultedStringField',
						'type' => 'string',
						'defaultValue' => '',
					],
				],
			],
		];
		$this->assertSame($expected, $result, print_r($result, true));
	}

}
