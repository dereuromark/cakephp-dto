<?php

namespace CakeDto\Test\TestCase\Dto;

use CakeDto\Engine\XmlValidator;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;

class XmlValidatorTest extends TestCase {

	/**
	 * @var string
	 */
	protected $validator;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->validator = XmlValidator::class;
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->validator);
	}

	/**
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function testValidateError() {
		$this->expectException(InvalidArgumentException::class);
		$exampleXml = ROOT . DS . 'tests/files/xml/validate_error.xml';

		try {
			/** @var XmlValidator $validator */
			$validator = $this->validator;
			$validator::validate($exampleXml);
		} catch (InvalidArgumentException $e) {
			$this->assertContains('The attribute \'noname\' is not allowed', $e->getMessage());

			throw $e;
		}
	}

	/**
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function testValidateFatalError() {
		$this->expectException(InvalidArgumentException::class);
		$exampleXml = ROOT . DS . 'tests/files/xml/validate_fatal_error.xml';

		try {
			/** @var XmlValidator $validator */
			$validator = $this->validator;
			$validator::validate($exampleXml);
		} catch (InvalidArgumentException $e) {
			$this->assertContains('Start tag expected', $e->getMessage());

			throw $e;
		}
	}

}
