<?php

require 'bootstrap.php';

use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOutput;
use Cake\Core\Configure;
use CakeDto\Collection\CakeCollectionAdapter;
use CakeDto\Console\Io;
use CakeDto\Filesystem\Folder;
use CakeDto\Generator\Builder;
use CakeDto\Generator\Generator;
use CakeDto\View\Renderer;
use PhpCollective\Dto\Collection\CollectionAdapterRegistry;
use PhpCollective\Dto\Engine\XmlEngine;
use PhpCollective\Dto\Engine\XmlValidator;

// Use CakePHP-DTO XSD for validation (different namespace than standalone)
XmlValidator::setXsdPath(ROOT . DS . 'config' . DS . 'dto.xsd');

// Register CakeCollectionAdapter for proper template generation
CollectionAdapterRegistry::register(new CakeCollectionAdapter());

Configure::write('CakeDto.scalarAndReturnTypes', true);
Configure::write('CakeDto.strictTypes', true);

$engine = new XmlEngine();
$builder = new Builder($engine);
$renderer = new Renderer();

$out = new ConsoleOutput();
$err = new ConsoleOutput();
$consoleIo = new ConsoleIo($out, $err);
$consoleIo->level($consoleIo::VERBOSE);
$io = new Io($consoleIo);

$generator = new Generator($builder, $renderer, $io);

$configPath = TMP . 'generate' . DS;
if (!is_dir($configPath . 'dto')) {
	mkdir($configPath . 'dto', 0700, true);
}
$srcPath = ROOT . DS . 'tests' . DS . 'test_app' . DS . 'src' . DS;

$dryRun = !empty($_SERVER['argv'][1]);
if (!$dryRun) {
	$folder = new Folder($srcPath . 'Dto');
	$folder->delete();
	if (!is_dir($srcPath . 'Dto')) {
		mkdir($srcPath . 'Dto', 0700, true);
	}
}

$xmls = [
	'basic.dto.xml',
	'immutable.dto.xml',
	'orm.dto.xml',
	'cake_collection.dto.xml',
	'enum.dto.xml',
];
foreach ($xmls as $xml) {
	$xmlPath = ROOT . DS . 'docs/examples/' . $xml;
	copy($xmlPath, $configPath . 'dto/' . $xml);
}

$options = [
	'namespace' => 'TestApp',
	'confirm' => true,
	'verbose' => true,
	'dryRun' => $dryRun,
];
$result = $generator->generate($configPath, $srcPath, $options);
if ($dryRun && $result !== $generator::CODE_SUCCESS) {
	echo ' => Please run `composer generate` to update the demo DTOs.' . PHP_EOL;
	exit($result);
}

$srcPath = ROOT . DS . 'tests' . DS . 'test_app' . DS . 'plugins' . DS . 'Sandbox' . DS . 'src' . DS;
$configPath = ROOT . DS . 'tests' . DS . 'test_app' . DS . 'plugins' . DS . 'Sandbox' . DS . 'config' . DS;
$options = [
	'namespace' => 'Sandbox',
	'confirm' => true,
	'verbose' => true,
	'dryRun' => $dryRun,
];
$result = $generator->generate($configPath, $srcPath, $options);

if ($dryRun && $result !== $generator::CODE_SUCCESS) {
	echo ' => Please run `composer generate` to update the demo DTOs.' . PHP_EOL;
	exit($result);
}
