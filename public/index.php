<?php
error_reporting(E_ALL ^ E_NOTICE);
//开启调试
define('DEBUG', true);
define('APP_PATH', realpath(dirname(__FILE__) . '/../app'));
//define('APP', realpath(dirname(__FILE__) . '/../app'));
define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public'));
define('APP_KEY', '');
//define('APP_SECRET', '');

$app = new \Yaf\Application(APP_PATH . '/conf/application.ini', ini_get('yaf.environ'));
$app->bootstrap()->run();
