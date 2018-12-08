<?php
namespace CakeDto\Test\TestCase\View;

use CakeDto\View\DtoView;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Http\Response;
use Cake\Http\ServerRequest as Request;
use Cake\TestSuite\StringCompareTrait;
use Cake\TestSuite\TestCase;

class DtoViewTest extends TestCase {
	use StringCompareTrait;

	/**
	 * @var \CakeDto\View\DtoView
	 */
	protected $View;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$request = new Request();
		$response = new Response();
		$this->View = new DtoView($request, $response);

		Configure::write(
			'App.paths.templates.x',
			Plugin::path('CakeDto') . 'tests' . DS . 'test_app' . DS . 'src' . DS . 'Template' . DS . 'Twig' . DS
		);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->View);
	}

	/**
	 * test rendering a template file
	 *
	 * @return void
	 */
	public function testRenderTemplate() {
		$this->View->set(['aVariable' => 123]);
		$result = $this->View->render('simple');
		$expected = "The value of aVariable is: 123.\n";

		$this->assertSame($expected, $result, 'variables in Twig tags should be evaluated');
	}

	/**
	 * verify that php tags are ignored
	 *
	 * @return void
	 */
	public function testRenderIgnorePhpTags() {
		$this->View->set(['aVariable' => 123]);
		$result = $this->View->render('simple_php');
		$expected = "The value of aVariable is: 123. Not <?php echo \$aVariable ?>.\n";

		$this->assertSame($expected, $result, 'variables in php tags should be treated as strings');
	}

	/**
	 * verify that short php tags are ignored
	 *
	 * @return void
	 */
	public function testRenderIgnorePhpShortTags() {
		$this->View->set(['aVariable' => 123]);
		$result = $this->View->render('simple_php_short_tags');
		$expected = "The value of aVariable is: 123. Not <?= \$aVariable ?>.\n";

		$this->assertSame($expected, $result, 'variables in php tags should be treated as strings');
	}

	/**
	 * Newlines after template tags should act predictably
	 *
	 * @return void
	 */
	public function testRenderNewlines() {
		$result = $this->View->render('newlines');
		$expected = "There should be a newline about here: \n";
		$expected .= "And this should be on the next line.\n";
		$expected .= "\n";
		$expected .= "There should be a single new line after this\n";

		$this->assertSame(
			$expected,
			$result,
			'Tags at the end of a line should not swallow new lines when rendered'
		);
	}

}
