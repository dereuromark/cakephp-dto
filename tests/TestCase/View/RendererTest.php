<?php

namespace CakeDto\Test\TestCase\View;

use CakeDto\View\Renderer;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

class RendererTest extends TestCase {

	/**
	 * @var \CakeDto\View\Renderer
	 */
	protected $renderer;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->renderer = new Renderer();

		Configure::write(
			'App.paths.templates.x',
			TEST_APP . DS . 'Template' . DS . 'Renderer' . DS
		);
		Configure::delete('CakeDto.strictTypes');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->builder);

		Configure::delete('CakeDto.strictTypes');
	}

	/**
	 * @return void
	 */
	public function testGenerate() {
		$data = [
			'name' => 'Foo',
			'plugin' => 'cakephp-foo',
		];

		$this->renderer->set($data);

		$content = $this->renderer->generate('demo');
		$expected = 'The package of ' . $data['name'] . ' is: ' . $data['plugin'] . ".\n";

		$this->assertSame($expected, $content, 'variables in Twig tags should be evaluated');
	}

	/**
	 * @return void
	 */
	public function testStrictTypes() {
		$data = [
			'name' => 'Foo',
		];

		$this->renderer->set($data);

		$content = $this->renderer->generate('strict');
		$expected = '<?php';

		$this->assertSame($expected, trim($content), 'Strict types should not be activated');

		Configure::write('CakeDto.strictTypes', true);
		$content = $this->renderer->generate('strict');
		$expected = '<?php declare(strict_types=1);';

		$this->assertSame($expected, trim($content), 'Strict types should not be activated');
	}

}
