<?php
namespace CakeDto\View;

use Cake\Core\Configure;
use Cake\Core\ConventionsTrait;
use Cake\Core\InstanceConfigTrait;
use Cake\Event\EventInterface;
use WyriHaximus\TwigView\View\TwigView;

class DtoView extends TwigView {

	use ConventionsTrait;
	use InstanceConfigTrait;

	/**
	 * This config is read when evaluating a template file.
	 *
	 * @var array
	 */
	protected $_defaultConfig = [
	];

	/**
	 * Templates extensions to search for.
	 *
	 * @var array
	 */
	protected $extensions = [
		'.twig',
	];

	/**
	 * Initialize view
	 *
	 * @return void
	 */
	public function initialize(): void {
		$dtoTemplates = dirname(dirname(__FILE__)) . DS . 'Template' . DS;
		$paths = (array)Configure::read('App.paths.templates');

		if (!in_array($dtoTemplates, $paths)) {
			$paths[] = $dtoTemplates;
			Configure::write('App.paths.templates', $paths);
		}

		parent::initialize();
	}

	/**
	 * Renders view for given view file and layout.
	 *
	 * Render triggers helper callbacks, which are fired before and after the view are rendered,
	 * as well as before and after the layout. The helper callbacks are called:
	 *
	 * - `beforeRender`
	 * - `afterRender`
	 *
	 * View names can point to plugin views/layouts. Using the `Plugin.view` syntax
	 * a plugin view/layout can be used instead of the app ones. If the chosen plugin is not found
	 * the view will be located along the regular view path cascade.
	 *
	 * View can also be a template string, rather than the name of a view file
	 *
	 * @param string|null $view Name of view file to use, or a template string to render
	 * @param string|false|null $layout Layout to use. Not used, for consistency with other views only
	 * @return string Rendered content.
	 */
	public function render(?string $view = null, $layout = null): string {
		$viewFileName = $this->_getTemplateFileName($view);

		$this->_currentType = static::TYPE_TEMPLATE;
		$this->dispatchEvent('View.beforeRender', [$viewFileName]);
		$this->Blocks->set('content', $this->_render($viewFileName));
		$this->dispatchEvent('View.afterRender', [$viewFileName]);

		if ($layout === null) {
			$layout = $this->layout;
		}
		if ($layout && $this->autoLayout) {
			$this->Blocks->set('content', $this->renderLayout('', $layout));
		}

		return rtrim($this->Blocks->get('content'), "\n") . "\n";
	}

	/**
	 * Wrapper for creating and dispatching events.
	 *
	 * Use the Bake prefix for bake related view events
	 *
	 * @param string $name Name of the event.
	 * @param array|null $data Any value you wish to be transported with this event to
	 * it can be read by listeners.
	 *
	 * @param object|null $subject The object that this event applies to
	 * ($this by default).
	 *
	 * @return \Cake\Event\EventInterface
	 */
	public function dispatchEvent(string $name, ?array $data = null, ?object $subject = null): EventInterface {
		$name = preg_replace('/^View\./', 'Dto.', $name);

		return parent::dispatchEvent($name, $data, $subject);
	}

	/**
	 * Return all possible paths to find view files in order
	 *
	 * @param string|null $plugin Optional plugin name to scan for view files.
	 * @param bool $cached Set to false to force a refresh of view paths. Default true.
	 * @return array paths
	 */
	protected function _paths(?string $plugin = null, bool $cached = true): array {
		$paths = parent::_paths($plugin, false);
		foreach ($paths as &$path) {
			$path .= 'Dto' . DS;
		}

		return $paths;
	}

}
