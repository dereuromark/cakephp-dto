<?php

namespace CakeDto\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use CakeDto\Console\Io;
use CakeDto\Generator\Builder;
use CakeDto\Generator\Generator;
use CakeDto\View\Renderer;
use PhpCollective\Dto\Engine\EngineInterface;
use PhpCollective\Dto\Engine\XmlEngine;
use RuntimeException;

class DtoGenerateCommand extends Command {

	/**
	 * @var int
	 */
	public const CODE_CHANGES = 2;

	/**
	 * @return string
	 */
	public static function getDescription(): string {
		return 'Generate DTOs from current config.';
	}

	/**
	 * E.g.:
	 * bin/cake upgrade /path/to/app --level=cakephp40
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 *
	 * @throws \Cake\Console\Exception\StopException
	 * @return int|null|void The exit code or null for success
	 */
	public function execute(Arguments $args, ConsoleIo $io) {
		$options = [
			'dryRun' => $args->getOption('dry-run'),
			'confirm' => $args->getOption('confirm'),
			'force' => $args->getOption('force'),
			'plugin' => $args->getOption('plugin'),
			'namespace' => $args->getOption('namespace'),
		] + (array)Configure::read('CakeDto');

		return $this->_generator($io)->generate($this->_getConfigPath($args), $this->_getSrcPath($args), $options);
	}

	/**
	 * @param \Cake\Console\Arguments $args
	 *
	 * @return string
	 */
	protected function _getSrcPath(Arguments $args): string {
		if ($args->getOption('plugin')) {
			$path = Plugin::path((string)$args->getOption('plugin'));

			return $path . 'src' . DS;
		}

		return ROOT . DS . 'src' . DS;
	}

	/**
	 * @param \Cake\Console\Arguments $args
	 *
	 * @return string
	 */
	protected function _getConfigPath(Arguments $args): string {
		if ($args->getOption('plugin')) {
			$path = Plugin::path((string)$args->getOption('plugin'));

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
		/** @phpstan-var class-string<\CakeDto\Generator\Builder> $builderClass */
		$builderClass = Configure::read('CakeDto.builder') ?: Builder::class;

		return new $builderClass($this->_engine());
	}

	/**
	 * @param \Cake\Console\ConsoleIo $io
	 *
	 * @return \CakeDto\Generator\Generator
	 */
	protected function _generator(ConsoleIo $io): Generator {
		/** @phpstan-var class-string<\CakeDto\Generator\Generator> $generatorClass */
		$generatorClass = Configure::read('CakeDto.generator') ?: Generator::class;

		return new $generatorClass($this->_builder(), $this->_renderer(), $this->io($io));
	}

	/**
	 * @throws \RuntimeException
	 * @return \PhpCollective\Dto\Engine\EngineInterface
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
	 * @param \Cake\Console\ConsoleIo $io
	 *
	 * @return \CakeDto\Console\Io
	 */
	protected function io(ConsoleIo $io): Io {
		return new Io($io);
	}

	/**
	 * @return \CakeDto\View\Renderer
	 */
	protected function _renderer(): Renderer {
		/** @phpstan-var class-string<\CakeDto\View\Renderer> $rendererClass */
		$rendererClass = Configure::read('CakeDto.renderer') ?: Renderer::class;

		return new $rendererClass();
	}

	/**
	 * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
	 *
	 * @return \Cake\Console\ConsoleOptionParser The built parser.
	 */
	protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
		$options = [
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
		];

		return $parser
			->setDescription(static::getDescription())
			->addOptions($options);
	}

}
