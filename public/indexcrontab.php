<?php
define("DEBUG", true);
define('APP_PATH', realpath(dirname(__FILE__) . '/../app'));
$app = new \Yaf\Application(APP_PATH . '/conf/application.ini', ini_get('yaf.environ'));
$app->bootstrap()->getDispatcher()->dispatch(new \Yaf\Request\Simple());
