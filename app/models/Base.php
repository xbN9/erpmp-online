<?php
use Yaf\Registry;

class BaseModel {
    protected static $db_resouce;
    protected static $dbconn;

    public function __construct() {
        self::$dbconn = Registry::get('DB');
    }

    public static function getSql($query = '') {
        if ($query) {
            $sqlStr   = $query->toSql();
            $sqlArr   = explode('?', $sqlStr);
            $paramArr = $query->getBindings();
            foreach ($sqlArr as $k => &$val) {
                if (isset($paramArr[$k])) {
                    $val .= $paramArr[$k];
                }
            }
            die(print_r(implode($sqlArr)));
        } else {
            echo 'no source';
        }
    }
}
