<?php declare(strict_types=1);
/**
 * !!! Auto generated file. Do not directly modify this file. !!!
 * You can either version control this or generate the file on the fly prior to usage/deployment.
 */

namespace Sandbox\Dto\Jira;

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
class IssueDto extends \CakeDto\Dto\AbstractDto {

	public const FIELD_ID = 'id';
	public const FIELD_KEY = 'key';
	public const FIELD_STATUS = 'status';
	public const FIELD_PRIORITY = 'priority';
	public const FIELD_SUMMARY = 'summary';
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
	protected $version;

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
	 * @param string $id
	 *
	 * @return $this
	 */
	public function setId(string $id) {
		$this->id = $id;
		$this->_touchedFields[self::FIELD_ID] = true;

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
	public function setKey(string $key) {
		$this->key = $key;
		$this->_touchedFields[self::FIELD_KEY] = true;

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
	public function setStatus(string $status) {
		$this->status = $status;
		$this->_touchedFields[self::FIELD_STATUS] = true;

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
	public function setPriority(string $priority) {
		$this->priority = $priority;
		$this->_touchedFields[self::FIELD_PRIORITY] = true;

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
	public function setSummary(string $summary) {
		$this->summary = $summary;
		$this->_touchedFields[self::FIELD_SUMMARY] = true;

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
	public function setVersion(?string $version) {
		$this->version = $version;
		$this->_touchedFields[self::FIELD_VERSION] = true;

		return $this;
	}

	/**
	 * @param string $version
	 *
	 * @throws \RuntimeException If value is not present.
	 *
	 * @return $this
	 */
	public function setVersionOrFail(string $version) {
		$this->version = $version;
		$this->_touchedFields[self::FIELD_VERSION] = true;

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

}
