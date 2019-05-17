<?php
namespace Dto\Engine;

use Cake\Utility\Xml;

class XmlEngine implements EngineInterface {

	const EXT = 'xml';

	/**
	 * @return string
	 */
	public function extension() {
		return static::EXT;
	}

	/**
	 * Validates files.
	 *
	 * @param array $files
	 * @return void
	 */
	public function validate(array $files) {
		/** @var \CakeDto\Engine\XmlValidator $class */
		$class = XmlValidator::class;

		foreach ($files as $file) {
			$class::validate($file);
		}
	}

	/**
	 * Parses content into array form. Can also already contain basic validation
	 * if validate() cannot be used.
	 *
	 * @param string $content
	 *
	 * @return array
	 */
	public function parse($content) {
		$xml = Xml::build($content);

		$array = Xml::toArray($xml);

		if (!isset($array['dtos']['dto'])) {
			return [];
		}

		$dtos = !isset($array['dtos']['dto'][0]) ? [$array['dtos']['dto']] : $array['dtos']['dto'];

		$result = [];
		foreach ($dtos as $dto) {
			$name = $dto['@name'];

			foreach ($dto as $key => $value) {
				if (mb_substr($key, 0, 1) !== '@') {
					continue;
				}
				$key = mb_substr($key, 1);
				$value = $this->castBoolValue($value, $key);

				$result[$name][$key] = $value;
			}

			if (!isset($dto['field'])) {
				$result[$name]['fields'] = [];
				continue;
			}

			if (!isset($dto['field'][0])) {
				$dto['field'] = [$dto['field']];
			}

			$fields = [];
			foreach ($dto['field'] as $fieldDefinition) {
				$fieldName = $fieldDefinition['@name'];
				foreach ($fieldDefinition as $k => $v) {
					$key = substr($k, 1);
					$v = $this->castBoolValue($v, $key);
					$v = $this->castDefaultValue($v, $key, $fieldDefinition);

					$fields[$fieldName][$key] = $v;
				}
			}
			$result[$name]['fields'] = $fields;
		}

		return $result;
	}

	/**
	 * @param string $value
	 * @param string|null $key
	 * @return string|bool
	 */
	protected function castBoolValue($value, $key = null) {
		if ($key && !in_array($key, ['required', 'immutable', 'collection', 'associative'])) {
			return $value;
		}

		if ($value === 'true') {
			return true;
		}
		if ($value === 'false') {
			return false;
		}

		return $value;
	}

	/**
	 * @param string $value
	 * @param string $key
	 * @param array $fieldDefinition
	 *
	 * @return string|int|float|bool
	 */
	protected function castDefaultValue($value, $key, $fieldDefinition) {
		if (!in_array($key, ['defaultValue']) || empty($fieldDefinition['@type'])) {
			return $value;
		}

		$type = $fieldDefinition['@type'];
		if ($type === 'int') {
			return (int)$value;
		}
		if ($type === 'float') {
			return (float)$value;
		}
		if ($type === 'bool') {
			return $this->castBoolValue($value);
		}

		return $value;
	}

}
