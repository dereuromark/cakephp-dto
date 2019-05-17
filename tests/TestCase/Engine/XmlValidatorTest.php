<?php

namespace Dto\Test\TestCase\Engine;

use Dto\Engine\XmlValidator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class XmlValidatorTest extends TestCase {

	/**
	 * @var string
	 */
	protected $validator;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->validator = XmlValidator::class;
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
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
			/** @var \CakeDto\Engine\XmlValidator $validator */
			$validator = $this->validator;
			$validator::validate($exampleXml);
		} catch (InvalidArgumentException $e) {
			$this->assertStringContainsString('The attribute \'noname\' is not allowed', $e->getMessage());

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
			/** @var \CakeDto\Engine\XmlValidator $validator */
			$validator = $this->validator;
			$validator::validate($exampleXml);
		} catch (InvalidArgumentException $e) {
			$this->assertStringContainsString('Start tag expected', $e->getMessage());

			throw $e;
		}
	}

}
