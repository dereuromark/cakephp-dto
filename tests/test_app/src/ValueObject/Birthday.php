<?php

namespace TestApp\ValueObject;

use Cake\I18n\FrozenDate;
use DateTimeImmutable;
use InvalidArgumentException;
use JsonSerializable;

/**
 * An example with a constructor with mixed types.
 *
 * This class represent a serialize birthday.
 */
class Birthday implements JsonSerializable {

	public const YEAR_MAX = 130;

	/**
	 * @var \Cake\I18n\FrozenDate
	 */
	protected $date;

	/**
	 * @param \Cake\I18n\FrozenDate|string $date
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($date) {
		if (!($date instanceof FrozenDate)) {
			$date = new FrozenDate($date);
		}

		$currentYear = getdate(time())['year'];
		if ($date->year > $currentYear || ($date->year + static::YEAR_MAX < $currentYear)) {
			throw new InvalidArgumentException('Invalid year: ' . $date->year);
		}

		$this->date = $date;
	}

	/**
	 * @param array $array
	 * @return static
	 */
	public static function createFromArray(array $array): self {
		return new static($array['year'] . '-' . $array['month'] . '-' . $array['day']);
	}

	/**
	 * @return int
	 */
	public function getAge(): int {
		$now = new DateTimeImmutable();

		return $now->diff($this->date)->y;
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		return [
			'year' => $this->date->year,
			'month' => $this->date->month,
			'day' => $this->date->day,
		];
	}

	/**
	 * @param string $birthdayString
	 *
	 * @return static
	 */
	public static function createFromString(string $birthdayString) {
		$date = new FrozenDate($birthdayString);

		return new static($date);
	}

	/**
	 * @return string
	 */
	public function serialize(): string {
		return json_encode($this->toArray());
	}

	/**
	 * Returns a (localized) string version.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return (string)$this->date;
	}

	/**
	 * @param string $serialized
	 * @return static
	 */
	public function unserialize(string $serialized): self {
		$array = json_decode($serialized, true);

		return static::createFromArray($array);
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string {
		return $this->__toString();
	}

}
