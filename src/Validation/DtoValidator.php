<?php

declare(strict_types=1);

namespace CakeDto\Validation;

use Cake\Validation\Validator;
use PhpCollective\Dto\Dto\Dto;

/**
 * Bridges DTO validation rules to CakePHP Validator.
 *
 * Usage:
 * ```php
 * $validator = DtoValidator::fromDto(new UserDto());
 * $errors = $validator->validate($data);
 * ```
 */
class DtoValidator {

	/**
     * Create a CakePHP Validator from DTO validation rules.
     *
     * @param \PhpCollective\Dto\Dto\Dto $dto A DTO instance to extract rules from.
     *
     * @return \Cake\Validation\Validator
     */
	public static function fromDto(Dto $dto): Validator {
		$validator = new Validator();

		foreach ($dto->validationRules() as $field => $rules) {
			if (!empty($rules['required'])) {
				$validator->requirePresence($field, true);
				$validator->notEmptyString($field);
			}

			if (isset($rules['minLength'])) {
				$validator->minLength($field, $rules['minLength']);
			}
			if (isset($rules['maxLength'])) {
				$validator->maxLength($field, $rules['maxLength']);
			}
			if (isset($rules['min'])) {
				$validator->greaterThanOrEqual($field, $rules['min']);
			}
			if (isset($rules['max'])) {
				$validator->lessThanOrEqual($field, $rules['max']);
			}
			if (isset($rules['pattern'])) {
				$validator->regex($field, $rules['pattern']);
			}
		}

		return $validator;
	}

}
