<?php

namespace CakeDto\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use CakeDto\Console\Io;
use CakeDto\Engine\EngineInterface;
use CakeDto\Engine\XmlEngine;
use CakeDto\Generator\Builder;
use CakeDto\Generator\Generator;
use CakeDto\View\Renderer;
use RuntimeException;

class DtoShell extends Shell {

	public const CODE_CHANGES = 2;

	/**
	 * @param string|null $name
	 * @return void
	 */
	public function init(?string $name = null): void {
		$path = $this->_getConfigPath();
		$this->out('Creating file in ' . $path, 1, static::VERBOSE);

		if ($name) {
			if (!is_dir($path . 'dto')) {
				mkdir($path . 'dto', 0700, true);
			}
			$file = 'dto' . DS . $name . '.dto.xml';
		} else {
			$file = 'dto.xml';
		}

		if (file_exists($path . $file)) {
			$this->abort('File already exists: ' . $file);
		}

		$content = file_get_contents(Plugin::path('CakeDto') . 'files' . DS . 'dto.xml');

		file_put_contents($path . $file, $content);
		$this->out('File generated: ' . $file);
	}

	/**
	 * @return int|null
	 */
	public function generate(): ?int {
		$options = [
			'dryRun' => $this->param('dry-run'),
			'confirm' => $this->param('confirm'),
			'force' => $this->param('force'),
			'plugin' => $this->param('plugin'),
			'namespace' => $this->param('namespace'),
		] + (array)Configure::read('CakeDto');

		return $this->_generator()->generate($this->_getConfigPath(), $this->_getSrcPath(), $options);
	}

	/**
	 * @return string
	 */
	protected function _getSrcPath(): string {
		if ($this->param('plugin')) {
			$path = Plugin::path((string)$this->param('plugin'));

			return $path . 'src' . DS;
		}

		return ROOT . DS . 'src' . DS;
	}

	/**
	 * @return string
	 */
	protected function _getConfigPath(): string {
		if ($this->param('plugin')) {
			$path = Plugin::path((string)$this->param('plugin'));

			return $path . 'config' . DS;
		}

		if (defined('CONFIG')) {
			return CONFIG;
		}

		return ROOT . DS . 'config' . DS;
	}

	/**
	 * @return \CakeDto\Generator\Builder
	 */
	protected function _builder(): Builder {
		$builderClass = Configure::read('CakeDto.builder') ?: Builder::class;

		return new $builderClass($this->_engine());
	}

	/**
	 * @return \CakeDto\Generator\Generator
	 */
	protected function _generator(): Generator {
		$generatorClass = Configure::read('CakeDto.generator') ?: Generator::class;

		return new $generatorClass($this->_builder(), $this->_renderer(), $this->_io());
	}

	/**
	 * @throws \RuntimeException
	 * @return \CakeDto\Engine\EngineInterface
	 */
	protected function _engine(): EngineInterface {
		$engineClass = Configure::read('CakeDto.engine') ?: XmlEngine::class;
		$engine = new $engineClass();
		if (!$engine instanceof EngineInterface) {
			throw new RuntimeException('Configured engine ' . $engineClass . ' is not a valid instance of ' . EngineInterface::class);
		}

		return $engine;
	}

	/**
	 * @return \CakeDto\Console\Io
	 */
	protected function _io(): Io {
		return new Io($this->getIo());
	}

	/**
	 * @return \CakeDto\View\Renderer
	 */
	protected function _renderer(): Renderer {
		$rendererClass = Configure::read('CakeDto.renderer') ?: Renderer::class;

		return new $rendererClass();
	}

	/**
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser(): ConsoleOptionParser {
		$parser = parent::getOptionParser();

		$generateParser = [
			'options' => [
				'dry-run' => [
					'short' => 'd',
					'help' => 'Dry run the task. This will output an error code `' . static::CODE_CHANGES . '` if files need changing. Can be used for CI checking or pre-commit hook.',
					'boolean' => true,
				],
				'plugin' => [
					'short' => 'p',
					'help' => 'The plugin to run it for. Defaults to the application otherwise.',
				],
				'namespace' => [
					'short' => 'n',
					'help' => 'Only needed if your app is not using the default `App` namespace.',
				],
				'confirm' => [
					'short' => 'c',
					'help' => 'Confirm with linting if the generated files are valid (PHP syntax check).',
					'boolean' => true,
				],
				'force' => [
					'short' => 'f',
					'help' => 'Force overwrite (needed for re-doing syntax check on existing ones).',
					'boolean' => true,
				],
			],
		];
		$initParser = [
			'options' => [
				'plugin' => [
					'short' => 'p',
					'help' => 'The plugin to run it for. Defaults to the application otherwise.',
				],
			],
		];

		$parser->addSubcommand('generate', [
				'help' => 'Generate DTOs from current config.',
				'parser' => $generateParser,
			])
			->addSubcommand('init', [
				'help' => 'Create a new file to start from in your /config dir.',
				'parser' => $initParser,
			]);

		return $parser;
	}

}
