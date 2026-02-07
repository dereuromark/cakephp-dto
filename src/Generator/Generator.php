<?php

namespace CakeDto\Generator;

use Cake\Command\Command;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use CakeDto\View\Renderer;
use Exception;
use PhpCollective\Dto\Generator\DiffHelperTrait;
use PhpCollective\Dto\Generator\IoInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Generator - Generates DTO classes from configuration.
 *
 * Reads DTO definitions from XML configuration files and generates
 * PHP class files with type-safe properties, getters, and setters.
 *
 * @author Mark Scherer
 * @license MIT
 */
class Generator {

	use DiffHelperTrait;

	/**
	 * @var int
	 */
	public const CODE_CHANGES = 2;
	public const CODE_SUCCESS = Command::CODE_SUCCESS;
	public const CODE_ERROR = Command::CODE_ERROR;

	/**
	 * @var \CakeDto\Generator\Builder
	 */
	protected $builder;

	/**
	 * @var \CakeDto\View\Renderer
	 */
	protected $renderer;

	/**
	 * @param \CakeDto\Generator\Builder $builder
	 * @param \CakeDto\View\Renderer $renderer
	 * @param \PhpCollective\Dto\Generator\IoInterface $io
	 */
	public function __construct(Builder $builder, Renderer $renderer, IoInterface $io) {
		$this->builder = $builder;
		$this->renderer = $renderer;
		$this->io = $io;
	}

	/**
	 * @param string $configPath
	 * @param string $srcPath
	 * @param array<string, mixed> $options
	 * @return int Code
	 */
	public function generate(string $configPath, string $srcPath, array $options = []): int {
		$options += [
			'force' => false,
			'dryRun' => false,
			'confirm' => false,
			'verbose' => false,
		];

		$definitions = [];
		try {
			$definitions = $this->builder->build($configPath, $options);
		} catch (Exception $e) {
			$this->io->abort($e->getMessage());
		}

		$dtos = $this->generateDtos($definitions);
		$foundDtos = [];
		if (!$options['force']) {
			$foundDtos = $this->findExistingDtos($srcPath . 'Dto' . DS);
		}

		$returnCode = static::CODE_SUCCESS;
		$changes = 0;
		foreach ($dtos as $name => $content) {
			$isNew = !isset($foundDtos[$name]);
			$isModified = !$isNew && $this->isModified($foundDtos[$name], $content);

			if (!$isNew && !$isModified) {
				unset($foundDtos[$name]);
				$this->io->out('Skipping: ' . $name . ' DTO', 1, ConsoleIo::VERBOSE);

				continue;
			}

			$target = $srcPath . 'Dto' . DS . $name . Configure::read('CakeDto.suffix', 'Dto') . '.php';
			$targetPath = dirname($target);
			if (!is_dir($targetPath)) {
				mkdir($targetPath, 0755, true);
			}

			if ($isModified) {
				$this->io->out('Changes in ' . $name . ' DTO:', 1, ConsoleIo::VERBOSE);
				$oldContent = file_get_contents($foundDtos[$name]) ?: '';
				$this->displayDiff($oldContent, $content);
			}
			if (!$options['dryRun']) {
				file_put_contents($target, $content);
				if ($options['confirm'] && !$this->checkPhpFileSyntax($target)) {
					$returnCode = static::CODE_ERROR;
				}
			}
			$changes++;

			unset($foundDtos[$name]);
			$this->io->success(($isModified ? 'Modifying' : 'Creating') . ': ' . $name . ' DTO');
		}

		foreach ($foundDtos as $name => $file) {
			if (!$options['dryRun']) {
				unlink($file);
			}
			$this->io->success('Deleting: ' . $name . ' DTO');
		}

		$this->io->verbose('Done, ' . $changes . ' file(s) changed.');

		if ($options['dryRun'] || $options['verbose']) {
			return $changes ? static::CODE_CHANGES : static::CODE_SUCCESS;
		}

		return static::CODE_SUCCESS;
	}

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	protected function findExistingDtos(string $path): array {
		if (!is_dir($path)) {
			mkdir($path, 0755, true);
		}

		$files = [];

		$directory = new RecursiveDirectoryIterator($path);
		$iterator = new RecursiveIteratorIterator($directory);
		foreach ($iterator as $fileInfo) {
			$file = $fileInfo->getPathname();
			$suffix = Configure::read('CakeDto.suffix', 'Dto');
			if (!preg_match('#src/Dto/(.+)' . $suffix . '\.php$#', $file, $matches)) {
				continue;
			}
			$name = $matches[1];
			$files[$name] = $file;
		}

		return $files;
	}

	/**
	 * @param string $file
	 * @param string $newContent
	 *
	 * @return bool
	 */
	protected function isModified(string $file, string $newContent): bool {
		return file_get_contents($file) !== $newContent;
	}

	/**
	 * @param array<string, mixed> $definitions
	 *
	 * @return array<string>
	 */
	protected function generateDtos(array $definitions): array {
		$dtos = [];
		foreach ($definitions as $name => $dto) {
			$this->renderer->set($dto);

			$content = $this->renderer->generate('dto');
			$dtos[$name] = $content;
		}

		return $dtos;
	}

	/**
	 * @param string $file
	 * @return bool
	 */
	protected function checkPhpFileSyntax(string $file): bool {
		exec('php -l ' . escapeshellarg($file), $output, $returnValue);

		if ($returnValue !== static::CODE_SUCCESS) {
			$this->io->error('PHP file invalid: ' . implode("\n", $output));

			return false;
		}

		return true;
	}

}
