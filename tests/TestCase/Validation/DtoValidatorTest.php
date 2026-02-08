<?php

declare(strict_types=1);

namespace CakeDto\Test\TestCase\Validation;

use Cake\TestSuite\TestCase;
use CakeDto\Validation\DtoValidator;
use TestApp\Dto\EmptyValidatedDto;
use TestApp\Dto\ValidatedDto;

class DtoValidatorTest extends TestCase {

	/**
	 * @return void
	 */
	public function testRequiredRule(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate(['name' => '']);
		$this->assertArrayHasKey('name', $errors);

		$errors = $validator->validate(['name' => 'John']);
		$this->assertArrayNotHasKey('name', $errors);
	}

	/**
	 * @return void
	 */
	public function testMinLengthRule(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate(['name' => 'A']);
		$this->assertArrayHasKey('name', $errors);

		$errors = $validator->validate(['name' => 'AB']);
		$this->assertArrayNotHasKey('name', $errors);
	}

	/**
	 * @return void
	 */
	public function testMaxLengthRule(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate(['name' => str_repeat('A', 51)]);
		$this->assertArrayHasKey('name', $errors);

		$errors = $validator->validate(['name' => str_repeat('A', 50)]);
		$this->assertArrayNotHasKey('name', $errors);
	}

	/**
	 * @return void
	 */
	public function testMinRule(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate(['name' => 'Valid', 'age' => -1]);
		$this->assertArrayHasKey('age', $errors);

		$errors = $validator->validate(['name' => 'Valid', 'age' => 0]);
		$this->assertArrayNotHasKey('age', $errors);
	}

	/**
	 * @return void
	 */
	public function testMaxRule(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate(['name' => 'Valid', 'age' => 151]);
		$this->assertArrayHasKey('age', $errors);

		$errors = $validator->validate(['name' => 'Valid', 'age' => 150]);
		$this->assertArrayNotHasKey('age', $errors);
	}

	/**
	 * @return void
	 */
	public function testPatternRule(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate(['name' => 'Valid', 'email' => 'invalid']);
		$this->assertArrayHasKey('email', $errors);

		$errors = $validator->validate(['name' => 'Valid', 'email' => 'test@example.com']);
		$this->assertArrayNotHasKey('email', $errors);
	}

	/**
	 * @return void
	 */
	public function testValidDataPassesAllRules(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate([
			'name' => 'John Doe',
			'email' => 'john@example.com',
			'age' => 30,
		]);

		$this->assertEmpty($errors);
	}

	/**
	 * @return void
	 */
	public function testInvalidDataReturnsErrors(): void {
		$dto = new ValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate([
			'name' => 'A',
			'email' => 'invalid',
			'age' => 200,
		]);

		$this->assertArrayHasKey('name', $errors);
		$this->assertArrayHasKey('email', $errors);
		$this->assertArrayHasKey('age', $errors);
	}

	/**
	 * @return void
	 */
	public function testEmptyDtoReturnsEmptyValidator(): void {
		$dto = new EmptyValidatedDto(null, true);
		$validator = DtoValidator::fromDto($dto);

		$errors = $validator->validate([]);
		$this->assertEmpty($errors);

		$errors = $validator->validate(['anything' => 'value']);
		$this->assertEmpty($errors);
	}

}
