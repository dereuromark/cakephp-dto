<?php

namespace CakeDto\Engine;

use Cake\Core\Plugin;
use DOMDocument;
use InvalidArgumentException;
use LibXMLError;

class XmlValidator {

	/**
	 * @param string $file
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public static function validate(string $file): void {
		// Enable user error handling
		libxml_use_internal_errors(true);

		$xml = new DOMDocument();
		$xml->load($file);

		$xsd = Plugin::path('CakeDto') . 'config' . DS . 'dto.xsd';
		if (!$xml->schemaValidate($xsd)) {
			$errors = static::getErrors();
			throw new InvalidArgumentException(implode("\n", $errors));
		}
	}

	/**
	 * @param \LibXMLError $error
	 *
	 * @return string|null
	 */
	public static function formatError(LibXMLError $error): ?string {
		$header = null;
		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				// We dont care for warnings right now, only hard fails.
				//$header = "Warning $error->code";
				break;
			case LIBXML_ERR_ERROR:
				$header = "Error `$error->code`";
				break;
			case LIBXML_ERR_FATAL:
				$header = "Fatal Error `$error->code`";
				break;
		}

		if ($header === null) {
			return $header;
		}

		$errorMessage = $header . ' ' . trim($error->message);
		if ($error->file) {
			$errorMessage .= " in `$error->file`";
		}
		$errorMessage .= " on line `$error->line`";

		return $errorMessage;
	}

	/**
	 * @return string[]
	 */
	public static function getErrors(): array {
		$errors = libxml_get_errors();

		$result = [];
		foreach ($errors as $error) {
			$return = static::formatError($error);
			if (!$return) {
				continue;
			}

			$result[] = $return;
		}
		libxml_clear_errors();

		return $result;
	}

}
