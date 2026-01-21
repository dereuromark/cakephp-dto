<?php

namespace CakeDto\View;

use Cake\Core\Configure;
use Cake\Core\ConventionsTrait;
use Cake\Core\InstanceConfigTrait;
use Cake\Event\EventInterface;
use Cake\TwigView\View\TwigView;
use CakeDto\Twig\Extension\DtoExtension;

class DtoView extends TwigView {

	use ConventionsTrait;
	use InstanceConfigTrait;

	/**
	 * Templates extensions to search for.
	 *
	 * @var array<string>
	 */
	protected array $extensions = [
		'.twig',
	];

	/**
	 * Initialize view
	 *
	 * @return void
	 */
	public function initialize(): void {
		$dtoTemplates = dirname(__DIR__, 2) . DS . 'templates' . DS;
		$paths = (array)Configure::read('App.paths.templates');

		if (!in_array($dtoTemplates, $paths, true)) {
			$paths[] = $dtoTemplates;
			Configure::write('App.paths.templates', $paths);
		}

		// Disable autoescape for code generation (matches php-collective/dto)
		$this->setConfig('environment.autoescape', false);

		parent::initialize();
	}

	/**
	 * Initialize Twig extensions.
	 *
	 * @return void
	 */
	protected function initializeExtensions(): void {
		parent::initializeExtensions();

		// Register custom DTO filters
		$this->getTwig()->addExtension(new DtoExtension());
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
	 * @inheritDoc
	 */
	public function dispatchEvent(string $name, array $data = [], ?object $subject = null): EventInterface {
		$name = preg_replace('/^View\./', 'Dto.', $name) ?? '';

		/** @var \Cake\View\View|null $subject */
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
