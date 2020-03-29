<?php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Yaf\Registry;

class Log {
    private static $log_path;
    private static $log;

    public function __construct($channel = 'main') {
        self::$log      = new Logger($channel);
        $config         = Registry::get('config');
        self::$log_path = $config['log']['path'] . '/';
    }

    public static function write($data, $str = 'info') {
        if (!isset($data['content'])) {
            return false;
        }
        if (!empty($str)) {
            switch ($str) {
            case 'warning':
                $log_param = Logger::WARNING;
                break;
            case 'error':
                $log_param = Logger::ERROR;
                break;
            case 'info':
                $log_param = Logger::INFO;
                break;
            case 'notice':
                $log_param = Logger::NOTICE;
                break;
            case 'critica':
                $log_param = Logger::CRITICA;
                break;
            case 'emergency':
                $log_param = Logger::EMERGENCY;
                break;
            }
            if (isset($data['name'])) {
                $log_file = self::$log_path.$data['name'] . '_' . date('Ymd') . '.log';
            } else {
                $log_file = self::$log_path .date('Ymd') . '.log';
            }
            self::$log->pushHandler(new StreamHandler($log_file, $log_param));
            call_user_func([self::$log, ucfirst($str)], $data['content']);
        }
    }
}
