<?php

namespace CakeDto\Test\TestCase\View;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\TestSuite\TestCase;
use CakeDto\View\Renderer;

class RendererTest extends TestCase {

	/**
	 * @var \CakeDto\View\Renderer
	 */
	protected $renderer;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->renderer = new Renderer();

		Configure::write(
			'App.paths.templates.x',
			Plugin::path('CakeDto') . 'tests' . DS . 'test_app' . DS . 'templates' . DS . 'Renderer' . DS,
		);
		Configure::delete('CakeDto.strictTypes');
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

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
		Configure::write('CakeDto.strictTypes', false);

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
