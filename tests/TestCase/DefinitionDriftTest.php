<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase;

use Cake\TestSuite\TestCase;
use PhpCollective\Dto\Engine\EngineInterface;
use PhpCollective\Dto\Engine\FileBasedEngineInterface;
use PhpCollective\Dto\Engine\NeonEngine;
use PhpCollective\Dto\Engine\PhpEngine;
use PhpCollective\Dto\Engine\XmlEngine;
use PhpCollective\Dto\Engine\YamlEngine;

/**
 * Drift tests across DTO definition formats.
 *
 * cakephp-dto can take its definitions from XML, YAML, NEON, or PHP. Each
 * format goes through its own engine and reaches the same Builder downstream,
 * so logically equivalent definition files MUST normalize to logically
 * equivalent definitions. When that drifts (a YAML shorthand parses
 * differently from the equivalent XML attribute, a default value loses its
 * type along the way, an `extends` chain is dropped in one engine but not
 * the others) the generator produces silently-different DTOs depending on
 * which file the user committed.
 *
 * Each test runs the *same logical* canonical DTO definition through all four
 * engines and asserts the post-parse field-shape is identical.
 */
class DefinitionDriftTest extends TestCase {

	/**
	 * Path to the fixture directory holding `canonical.dto.{xml,yml,neon,php}`.
	 */
	protected string $fixturesPath;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->fixturesPath = TESTS . 'Fixture' . DS . 'drift' . DS;
	}

	/**
	 * Each engine's `parse()` (or `parseFile()`) returns a per-file map
	 * `{DtoName => ['name' => ..., 'fields' => [...]]}`. The shape under each
	 * top-level DTO is what matters — DTO names + field names + per-field
	 * type/required/defaultValue/extends/collection/associative flags. The
	 * normalized shape produced below collapses minor cosmetic differences
	 * (key ordering inside a field array, missing-vs-default flags) so the
	 * comparison stays focused on semantic drift, not format-specific noise.
	 *
	 * @return void
	 */
	public function testParseProducesEquivalentNormalizedShapeAcrossFormats(): void {
		$xml = $this->normalize($this->parse(new XmlEngine(), 'xml'));
		$yaml = $this->normalize($this->parse(new YamlEngine(), 'yml'));
		$neon = $this->normalize($this->parse(new NeonEngine(), 'neon'));
		$php = $this->normalize($this->parse(new PhpEngine(), 'php'));

		// All four formats must agree on which DTOs are defined.
		$expectedDtoNames = ['DriftArticle', 'DriftAuthor', 'DriftFeaturedArticle'];
		$this->assertSame($expectedDtoNames, array_keys($xml), 'XML produced unexpected DTO set');
		$this->assertSame(array_keys($xml), array_keys($yaml), 'YAML differs from XML in DTO set');
		$this->assertSame(array_keys($xml), array_keys($neon), 'NEON differs from XML in DTO set');
		$this->assertSame(array_keys($xml), array_keys($php), 'PHP differs from XML in DTO set');

		// The XML shape is the reference — RssView-style xml is the format
		// that has the schema definition and the most stability guarantees.
		$this->assertSame($xml, $yaml, 'YAML drifts from XML');
		$this->assertSame($xml, $neon, 'NEON drifts from XML');
		$this->assertSame($xml, $php, 'PHP drifts from XML');
	}

	/**
	 * Spot-check one canonical field across formats — proves the test
	 * harness actually compares field shape and not just top-level keys.
	 *
	 * @return void
	 */
	public function testIndividualFieldShapeAgreesAcrossFormats(): void {
		foreach (['xml', 'yml', 'neon', 'php'] as $format) {
			$engine = match ($format) {
				'xml' => new XmlEngine(),
				'yml' => new YamlEngine(),
				'neon' => new NeonEngine(),
				'php' => new PhpEngine(),
			};
			$dtos = $this->normalize($this->parse($engine, $format));

			$this->assertArrayHasKey('DriftArticle', $dtos, "$format: missing DriftArticle");
			$fields = $dtos['DriftArticle']['fields'];

			$this->assertArrayHasKey('id', $fields, "$format: missing 'id'");
			$this->assertSame('int', $fields['id']['type'], "$format: 'id' type drift");
			$this->assertTrue($fields['id']['required'], "$format: 'id' required drift");

			$this->assertSame('string[]', $fields['tags']['type'], "$format: 'tags' collection type drift");
			$this->assertSame('DriftAuthor', $fields['author']['type'], "$format: 'author' nested type drift");

			// `defaultValue: false` must survive as a bool, not the string "false".
			// This is the most common silent-drift trap when YAML/NEON parsers
			// don't honor the schema type hint.
			$this->assertArrayHasKey('defaultValue', $fields['published'], "$format: 'published' default missing");
			$this->assertFalse($fields['published']['defaultValue'], "$format: 'published' default lost its bool type");
		}
	}

	/**
	 * @param \PhpCollective\Dto\Engine\EngineInterface $engine
	 * @param string $extension
	 * @return array<string, mixed>
	 */
	protected function parse(EngineInterface $engine, string $extension): array {
		$file = $this->fixturesPath . 'canonical.dto.' . $extension;
		$this->assertFileExists($file, "fixture missing for .$extension");

		if ($engine instanceof FileBasedEngineInterface) {
			return $engine->parseFile($file);
		}
		$content = file_get_contents($file);
		$this->assertNotFalse($content, "could not read $file");

		return $engine->parse($content);
	}

	/**
	 * Reduce a parsed-definition array to its semantic shape. Drops keys
	 * that are inert metadata (the `name` echo of the array key, format-
	 * specific marker keys, etc.) and sorts the field array so insertion
	 * order doesn't accidentally fail the assertion.
	 *
	 * Field-level keys are also sorted, and unset keys are dropped so a
	 * field that parses as `['type' => 'string', 'required' => null]` in
	 * one format compares equal to `['type' => 'string']` in another.
	 *
	 * @param array<string, mixed> $parsed
	 * @return array<string, array<string, mixed>>
	 */
	protected function normalize(array $parsed): array {
		$out = [];
		foreach ($parsed as $dtoName => $dto) {
			if (!is_array($dto)) {
				continue;
			}
			$norm = [];
			if (isset($dto['extends'])) {
				$norm['extends'] = $dto['extends'];
			}
			if (isset($dto['deprecated'])) {
				$norm['deprecated'] = $dto['deprecated'];
			}

			$fields = [];
			foreach ((array)($dto['fields'] ?? []) as $fieldName => $field) {
				if (!is_array($field)) {
					$field = ['type' => (string)$field];
				}
				// Strip name echo + drop nulls so engines that null-fill
				// optional keys compare equal to engines that omit them.
				unset($field['name']);
				$field = array_filter($field, static fn ($v) => $v !== null);
				ksort($field);
				$fields[$fieldName] = $field;
			}
			ksort($fields);

			$norm['fields'] = $fields;
			$out[$dtoName] = $norm;
		}
		ksort($out);

		return $out;
	}

}
