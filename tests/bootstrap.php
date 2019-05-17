<?php

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', dirname(__DIR__));
define('TMP', ROOT . DS . 'tmp' . DS);
if (!is_dir(TMP)) {
	mkdir(TMP, 0770, true);
}
define('CONFIG', ROOT . DS . 'config' . DS);
define('TESTS', ROOT . DS . 'tests' . DS);

define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);

require dirname(__DIR__) . '/vendor/autoload.php';
require CONFIG . 'bootstrap.php';

//Cake\Core\Configure::write('CakeDto', []);
