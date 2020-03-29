<?php
defined("APP_PATH") || define("APP_PATH", realpath(dirname(__FILE__) . "/../../app/"));

$app = new \Yaf\Application(APP_PATH . "/conf/application.ini");
$app->bootstrap();
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Yaf\Registry;

$commands = [];
$em = Registry::get('DB');
// return ConsoleRunner::createHelperSet($em);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
    array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
    )
);
ConsoleRunner::run($helperSet);
// return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);
$app->run();
