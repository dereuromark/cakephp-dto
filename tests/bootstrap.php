<?php

use Cake\Core\Configure;
use Cake\Core\Plugin;
use CakeDto\CakeDtoPlugin;

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', dirname(__DIR__));
define('APP_DIR', 'src');

define('APP', rtrim(sys_get_temp_dir(), DS) . DS . APP_DIR . DS);
if (!is_dir(APP)) {
	mkdir(APP, 0770, true);
}

define('TMP', ROOT . DS . 'tmp' . DS);
if (!is_dir(TMP)) {
	mkdir(TMP, 0770, true);
}
define('CONFIG', ROOT . DS . 'config' . DS);
define('TESTS', ROOT . DS . 'tests' . DS);

define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);

define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . APP_DIR . DS);

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config/bootstrap.php';

Configure::write('App', [
	'namespace' => 'App',
	'encoding' => 'utf-8',
	'paths' => [
		'templates' => [
			ROOT . DS . 'templates' . DS,
			ROOT . DS . 'tests' . DS . 'test_app' . DS . 'templates' . DS,
		],
	],
]);

Configure::write('CakeDto', [
]);

Configure::write('debug', true);

Plugin::getCollection()->add(new CakeDtoPlugin());
