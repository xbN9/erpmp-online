<?php
use Yaf\Application;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class DBAdapter
{
    private static $db_config;

    public static $isDevMode = true;

    private static $conn = null;

    private function __construct()
    {
        static::$db_config = Application::app()->getConfig()->toArray();
    }

    public static function getInstance($mode = true)
    {
        static::$isDevMode = $mode;
        if (self::$conn == null) {
            new self();
            self::$conn = self::connection();
        }
        return self::$conn;
    }

    private static function connection()
    {
        $path = array(static::$db_config['database']['entity']);
        $loader = new Doctrine\Common\ClassLoader('Entity', static::$db_config['database']['entity']);
        $loader->register();
        $loader = new Doctrine\Common\ClassLoader('EntityProxy', static::$db_config['database']['entity']);
        $loader->register();
        if (static::$isDevMode) {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        }
        $config = new Doctrine\ORM\Configuration();
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(static::$db_config['database']['entity']);
        $config->setProxyNamespace('EntityProxy');
        $config->setAutoGenerateProxyClasses(true);
        if (static::$isDevMode) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }
        Doctrine\Common\Annotations\AnnotationRegistry::registerFile(APP_PATH .'/../library/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
        //平时调用时使用
        $driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
            new Doctrine\Common\Annotations\CachedReader(
                new Doctrine\Common\Annotations\AnnotationReader(),
                new Doctrine\Common\Cache\ArrayCache()
            ),
            $path
        );
        $config->setMetadataDriverImpl($driver);
        $config->setMetadataCacheImpl($cache);
        $db_config = static::$db_config['database']['config'];
        //多个读或者写要考虑随机写入或者读array_rand
        $dbParams_slave = [];
        $options  = [
            PDO::MYSQL_ATTR_INIT_COMMAND        => "SET NAMES 'UTF8'",
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY  => false,
            PDO::ATTR_CASE                      => PDO::CASE_LOWER,
            PDO::ATTR_TIMEOUT                   => 5,
            PDO::ATTR_EMULATE_PREPARES          => false,
            PDO::ATTR_ERRMODE                   => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS              => PDO::NULL_EMPTY_STRING
        ];
        foreach ($db_config['slave'] as $val) {
            $dbParams_slave[] = [
                'user'     => $val['username'],
                'password' => $val['password'],
                'dbname'   => $val['db_name'],
                'port'     => $val['port'],
                'host'     => $val['host'],
                'charset'  =>$val['charset'],
                $options
            ];
        }
        $dbParams_master = [
            'user'     => $db_config['master']['username'],
            'password' => $db_config['master']['password'],
            'dbname'   => $db_config['master']['db_name'],
            'port'     => $db_config['master']['port'],
            'host'     => $db_config['master']['host'],
            'charset'  => $db_config['master']['charset'],
            $options
        ];
        $conn = DriverManager::getConnection(
            [
                'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
                'driver' => 'pdo_mysql',
                'master' => $dbParams_master,
                'slaves' => $dbParams_slave
            ]
        );
        $entityManager = EntityManager::create($conn, $config);
        return $entityManager;
    }

    private function __clone()
    {
    }

    public function __destruct()
    {
        self::$conn = null;
    }
}
