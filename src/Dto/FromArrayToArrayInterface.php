<?php

namespace CakeDto\Dto;

/**
 * Implement this interface for your VOs that should be fromArray()/toArray() safe.
 */
interface FromArrayToArrayInterface {

	/**
	 * @param array<string, mixed> $array
	 * @return static
	 */
	public static function createFromArray(array $array);

	/**
	 * @return array
	 */
	public function toArray(): array;

}
