<?php

namespace Dto\Dto;

/**
 * Implement this interface for your VOs that should be fromArray()/toArray() safe.
 */
interface FromArrayToArrayInterface {

	/**
	 * @param array $array
	 * @return static
	 */
	public static function createFromArray(array $array);

	/**
	 * @return array
	 */
	public function toArray();

}
