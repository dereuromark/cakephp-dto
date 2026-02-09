<?php

declare(strict_types=1);

namespace TestApp\Dto;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * Test DTO with validation rules for DtoValidator testing.
 */
class ValidatedDto extends AbstractDto {

	public const FIELD_NAME = 'name';

	public const FIELD_EMAIL = 'email';

	public const FIELD_AGE = 'age';

	protected ?string $name = null;

	protected ?string $email = null;

	protected ?int $age = null;

	/**
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'name' => [
			'name' => 'name',
			'type' => 'string',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'transformFrom' => null,
			'transformTo' => null,
			'isClass' => false,
			'enum' => null,
		],
		'email' => [
			'name' => 'email',
			'type' => 'string',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'transformFrom' => null,
			'transformTo' => null,
			'isClass' => false,
			'enum' => null,
		],
		'age' => [
			'name' => 'age',
			'type' => 'int',
			'required' => false,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
			'transformFrom' => null,
			'transformTo' => null,
			'isClass' => false,
			'enum' => null,
		],
	];

	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function validationRules(): array {
		return [
			'name' => [
				'required' => true,
				'minLength' => 2,
				'maxLength' => 50,
			],
			'email' => [
				'pattern' => '/^[^@]+@[^@]+$/',
			],
			'age' => [
				'min' => 0,
				'max' => 150,
			],
		];
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		return [
			'name' => $this->name,
			'email' => $this->email,
			'age' => $this->age,
		];
	}

	/**
	 * @param array<string, mixed> $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return new static($data, $ignoreMissing, $type);
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName(string $name) {
		$this->name = $name;
		$this->_touchedFields['name'] = true;

		return $this;
	}

	/**
	 * @param string $email
	 *
	 * @return $this
	 */
	public function setEmail(string $email) {
		$this->email = $email;
		$this->_touchedFields['email'] = true;

		return $this;
	}

	/**
	 * @param int $age
	 *
	 * @return $this
	 */
	public function setAge(int $age) {
		$this->age = $age;
		$this->_touchedFields['age'] = true;

		return $this;
	}

}
