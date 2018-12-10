<?php

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

define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);

define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . APP_DIR . DS);

require dirname(__DIR__) . '/vendor/autoload.php';
require CONFIG . 'bootstrap.php';
require CORE_PATH . 'config/bootstrap.php';

Cake\Core\Configure::write('App', [
	'namespace' => 'App',
	'encoding' => 'utf-8',
	'paths' => [
		'templates' => [
			ROOT . DS . 'src' . DS . 'Template' . DS,
			ROOT . DS . 'tests' . DS . 'test_app' . DS . 'src' . DS . 'Template' . DS
		],
	]
]);

Cake\Core\Configure::write('CakeDto', [
]);

Cake\Core\Configure::write('debug', true);

Cake\Core\Plugin::getCollection()->add(new CakeDto\Plugin());
//Cake\Core\Plugin::load('WyriHaximus/TwigView', ['path' => ROOT . DS . 'vendor/wyrihaximus/twig-view/', 'autoload' => true, 'bootstrap' => true]);
