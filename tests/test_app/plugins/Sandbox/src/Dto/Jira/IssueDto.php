<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace Sandbox\Dto\Jira;

use PhpCollective\Dto\Dto\AbstractDto;

/**
 * Jira/Issue DTO
 *
 * @property string $id
 * @property string $key
 * @property string $status
 * @property string $priority
 * @property string $summary
 * @property string|null $version
 */
class IssueDto extends AbstractDto {

	/**
	 * @var string
	 */
	public const FIELD_ID = 'id';

	/**
	 * @var string
	 */
	public const FIELD_KEY = 'key';

	/**
	 * @var string
	 */
	public const FIELD_STATUS = 'status';

	/**
	 * @var string
	 */
	public const FIELD_PRIORITY = 'priority';

	/**
	 * @var string
	 */
	public const FIELD_SUMMARY = 'summary';

	/**
	 * @var string
	 */
	public const FIELD_VERSION = 'version';


	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @var string
	 */
	protected $priority;

	/**
	 * @var string
	 */
	protected $summary;

	/**
	 * @var string|null
	 */
	protected ?string $version = null;

	/**
	 * Some data is only for debugging for now.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected array $_metadata = [
		'id' => [
			'name' => 'id',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'key' => [
			'name' => 'key',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'status' => [
			'name' => 'status',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'priority' => [
			'name' => 'priority',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'summary' => [
			'name' => 'summary',
			'type' => 'string',
			'required' => true,
			'defaultValue' => null,
			'dto' => null,
			'collectionType' => null,
			'associative' => false,
			'key' => null,
			'serialize' => null,
			'factory' => null,
			'mapFrom' => null,
			'mapTo' => null,
		],
		'version' => [
			'name' => 'version',
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
		],
	];

	/**
	 * @var array<string, array<string, string>>
	 */
	protected array $_keyMap = [
		'underscored' => [
			'id' => 'id',
			'key' => 'key',
			'status' => 'status',
			'priority' => 'priority',
			'summary' => 'summary',
			'version' => 'version',
		],
		'dashed' => [
			'id' => 'id',
			'key' => 'key',
			'status' => 'status',
			'priority' => 'priority',
			'summary' => 'summary',
			'version' => 'version',
		],
	];

	/**
	 * Whether this DTO is immutable.
	 *
	 * @var bool
	 */
	protected const IS_IMMUTABLE = false;

	/**
	 * Whether this DTO has generated fast-path methods.
	 *
	 * @var bool
	 */
	protected const HAS_FAST_PATH = true;

	/**
	 * Pre-computed setter method names for fast lookup.
	 *
	 * @var array<string, string>
	 */
	protected static array $_setters = [
		'id' => 'setId',
		'key' => 'setKey',
		'status' => 'setStatus',
		'priority' => 'setPriority',
		'summary' => 'setSummary',
		'version' => 'setVersion',
	];

	/**
	 * Optimized array assignment without dynamic method calls.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	protected function setFromArrayFast(array $data): void {
		if (isset($data['id'])) {
			/** @var string $value */
			$value = $data['id'];
			$this->id = $value;
			$this->_touchedFields['id'] = true;
		}
		if (isset($data['key'])) {
			/** @var string $value */
			$value = $data['key'];
			$this->key = $value;
			$this->_touchedFields['key'] = true;
		}
		if (isset($data['status'])) {
			/** @var string $value */
			$value = $data['status'];
			$this->status = $value;
			$this->_touchedFields['status'] = true;
		}
		if (isset($data['priority'])) {
			/** @var string $value */
			$value = $data['priority'];
			$this->priority = $value;
			$this->_touchedFields['priority'] = true;
		}
		if (isset($data['summary'])) {
			/** @var string $value */
			$value = $data['summary'];
			$this->summary = $value;
			$this->_touchedFields['summary'] = true;
		}
		if (isset($data['version'])) {
			/** @var string|null $value */
			$value = $data['version'];
			$this->version = $value;
			$this->_touchedFields['version'] = true;
		}
	}

	/**
	 * Optimized toArray for default type without dynamic dispatch.
	 *
	 * @return array<string, mixed>
	 */
	protected function toArrayFast(): array {
		return [
			'id' => $this->id,
			'key' => $this->key,
			'status' => $this->status,
			'priority' => $this->priority,
			'summary' => $this->summary,
			'version' => $this->version,
		];
	}


	/**
	 * Optimized setDefaults - only processes fields with default values.
	 *
	 * @return $this
	 */
	protected function setDefaults(): static {

		return $this;
	}

	/**
	 * Optimized validate - only checks required fields.
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	protected function validate(): void {
		if ($this->id === null || $this->key === null || $this->status === null || $this->priority === null || $this->summary === null) {
			$errors = [];
			if ($this->id === null) {
				$errors[] = 'id';
			}
			if ($this->key === null) {
				$errors[] = 'key';
			}
			if ($this->status === null) {
				$errors[] = 'status';
			}
			if ($this->priority === null) {
				$errors[] = 'priority';
			}
			if ($this->summary === null) {
				$errors[] = 'summary';
			}
			if ($errors) {
				throw new \InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
			}
		}
	}


	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function setId(string $id): static {
		$this->id = $id;
		$this->_touchedFields[static::FIELD_ID] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function hasId(): bool {
		return $this->id !== null;
	}

	/**
	 * @param string $key
	 *
	 * @return $this
	 */
	public function setKey(string $key): static {
		$this->key = $key;
		$this->_touchedFields[static::FIELD_KEY] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * @return bool
	 */
	public function hasKey(): bool {
		return $this->key !== null;
	}

	/**
	 * @param string $status
	 *
	 * @return $this
	 */
	public function setStatus(string $status): static {
		$this->status = $status;
		$this->_touchedFields[static::FIELD_STATUS] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getStatus(): string {
		return $this->status;
	}

	/**
	 * @return bool
	 */
	public function hasStatus(): bool {
		return $this->status !== null;
	}

	/**
	 * @param string $priority
	 *
	 * @return $this
	 */
	public function setPriority(string $priority): static {
		$this->priority = $priority;
		$this->_touchedFields[static::FIELD_PRIORITY] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPriority(): string {
		return $this->priority;
	}

	/**
	 * @return bool
	 */
	public function hasPriority(): bool {
		return $this->priority !== null;
	}

	/**
	 * @param string $summary
	 *
	 * @return $this
	 */
	public function setSummary(string $summary): static {
		$this->summary = $summary;
		$this->_touchedFields[static::FIELD_SUMMARY] = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSummary(): string {
		return $this->summary;
	}

	/**
	 * @return bool
	 */
	public function hasSummary(): bool {
		return $this->summary !== null;
	}

	/**
	 * @param string|null $version
	 *
	 * @return $this
	 */
	public function setVersion(?string $version): static {
		$this->version = $version;
		$this->_touchedFields[static::FIELD_VERSION] = true;

		return $this;
	}

	/**
	 * @param string $version
	 *
	 * @return $this
	 */
	public function setVersionOrFail(string $version): static {
		$this->version = $version;
		$this->_touchedFields[static::FIELD_VERSION] = true;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getVersion(): ?string {
		return $this->version;
	}

	/**
	 * @throws \RuntimeException If value is not set.
	 *
	 * @return string
	 */
	public function getVersionOrFail(): string {
		if ($this->version === null) {
			throw new \RuntimeException('Value not set for field `version` (expected to be not null)');
		}

		return $this->version;
	}

	/**
	 * @return bool
	 */
	public function hasVersion(): bool {
		return $this->version !== null;
	}

	/**
	 * @param string|null $type
	 * @param array<string>|null $fields
	 * @param bool $touched
	 *
	 * @return array{id: string, key: string, status: string, priority: string, summary: string, version: string|null}
	 */
	public function toArray(?string $type = null, ?array $fields = null, bool $touched = false): array {
		/** @var array{id: string, key: string, status: string, priority: string, summary: string, version: string|null} $result */
		$result = $this->_toArrayInternal($type, $fields, $touched);

		return $result;
	}

	/**
	 * @param array{id: string, key: string, status: string, priority: string, summary: string, version: string|null} $data
	 * @phpstan-param array<string, mixed> $data
	 * @param bool $ignoreMissing
	 * @param string|null $type
	 *
	 * @return static
	 */
	public static function createFromArray(array $data, bool $ignoreMissing = false, ?string $type = null): static {
		return static::_createFromArrayInternal($data, $ignoreMissing, $type);
	}

}
