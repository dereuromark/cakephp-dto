<?php

namespace CakeDto\Importer\Builder;

class SchemaBuilder implements BuilderInterface {

	/**
	 * @param string $name
	 * @param array $input
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function build(string $name, array $input, array $options = []): string {
		$fields = [];
		foreach ($input as $fieldName => $fieldDetails) {
			if (!$fieldDetails['_include']) {
				continue;
			}

			$attr = [
				'name="' . $fieldName . '"',
				'type="' . $fieldDetails['type'] . '"',
			];
			if (!empty($fieldDetails['required'])) {
				$attr[] = 'required="true"';
			}

			$fields[] = "\t\t" . '<field ' . implode(' ', $attr) . '/>';
		}

		$fields = implode("\n", $fields);
		$xml = <<<XML
	<dto name="$name">
$fields
	</dto>
XML;

		return $xml;
	}

}
