<?php

namespace CakeDto\Importer\Builder;

class SchemaBuilder implements BuilderInterface {

	/**
	 * @param string $name
	 * @param array<string, mixed> $input
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function build(string $name, array $input, array $options = []): string {
		$fields = [];
		foreach ($input as $fieldName => $fieldDetails) {
			if (isset($fieldDetails['_include']) && !$fieldDetails['_include']) {
				continue;
			}

			$attr = [
				'name="' . $this->escapeXmlAttr($fieldName) . '"',
				'type="' . $this->escapeXmlAttr($fieldDetails['type']) . '"',
			];
			if (!empty($fieldDetails['required'])) {
				$attr[] = 'required="true"';
			}
			if (!empty($fieldDetails['singular'])) {
				$attr[] = 'singular="' . $this->escapeXmlAttr($fieldDetails['singular']) . '"';
			}
			if (!empty($fieldDetails['associative'])) {
				$attr[] = 'associative="true"';
				$attr[] = 'key="' . $this->escapeXmlAttr($fieldDetails['associative']) . '"';
			}

			$fields[] = "\t\t" . '<field ' . implode(' ', $attr) . '/>';
		}

		$fields = implode("\n", $fields);
		$escapedName = $this->escapeXmlAttr($name);
		$xml = <<<XML
	<dto name="$escapedName">
$fields
	</dto>
XML;

		return $xml;
	}

	/**
	 * Escape a string for use in an XML attribute.
	 *
	 * @param string $value
	 * @return string
	 */
	protected function escapeXmlAttr(string $value): string {
		return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
	}

}
