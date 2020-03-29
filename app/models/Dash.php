<?php

class DashModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public static function toDayOrder($type = 1) {
        $now = date('Y-m-d');
        $sql = 'select sum(pay_price) tprice,count(distinct parent_order_sn) torders from `order`';
        if ($type == 1) {
            $sql .= " where status in (2,3) and date_format(create_time,'%Y-%m-%d')='$now'";
        } elseif ($type == 2) {
            $now = date('Y-m-d', strtotime('-1 days'));
            $sql .= " where status in (2,3) and date_format(create_time,'%Y-%m-%d')='$now'";
        } elseif ($type == 3) {
            $now = date('Y-m-d', strtotime('-2 days'));
            $sql .= " where status in (2,3) and date_format(create_time,'%Y-%m-%d')='$now'";
        } else {
            $now = date('Y-m-d', strtotime('-7 days'));
            $sql .= " where status in (2,3) and date_format(create_time,'%Y-%m-%d') >= '$now'";
        }
        $res = self::$dbconn::connection()->select($sql);
        //$res = Order::arrwhere($where)->select(['sum(pay_price),count(parent_order_sn)'])->get()->toArray();
        if ($res) {
            return json_decode(json_encode($res), true)[0];
        }
        return false;
    }

    public static function toData($type = 1) {
        $sql = 'select count(distinct parent_order_sn) torders from `order`';
        if ($type == 1) {
            $sql .= ' where status=1';
        } elseif ($type == 2) {
            $sql .= ' where status=2';
        } elseif ($type == 3) {
            $sql .= ' where status=3';
        } elseif ($type == 4) {
            $sql .= ' where status=7';
        }
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return json_decode(json_encode($res), true)[0];
        }
        return false;
    }

    public static function toProduct($type = 1) {
        $sql = 'SELECT count(g.id) sku FROM `goods` g LEFT JOIN product p ON g.product_id=p.id';
        if ($type == 1) {
            $sql .= ' where p.status = 1 AND g.status=1';
        } elseif ($type == 2) {
            $sql .= ' where p.status in (2,3)  AND g.status in (2.3)';
        } elseif ($type == 3) {
            $sql = 'select count(id) sku from `goods`';
        }
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return json_decode(json_encode($res), true)[0];
        }
        return false;
    }

    public static function toUser($type = 1) {
        $sql = 'select count(id) cnt from user_info';
        if ($type == 1) {
            $now = date('Y-m-d');
            $sql .= " where status=1 and date_format(create_time,'%Y-%m-%d')='$now'";
        } elseif ($type == 2) {
            $now = date('Y-m-d', strtotime('-1 days'));
            $sql .= " where status=1 and date_format(create_time,'%Y-%m-%d')='$now'";
        } elseif ($type == 3) {
            $now = date('Y-m');
            $sql .= " where status=1 and date_format(create_time,'%Y-%m')='$now'";
        } else {
            $sql .= ' where status=1';
        }
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return json_decode(json_encode($res), true)[0];
        }
        return false;
    }

    public static function toOrderChannel() {
        $sql = 'SELECT order_channel,count(parent_order_sn) cnt FROM `order`';
        $sql .= 'WHERE `status` IN (2,3,5,6) GROUP BY order_channel order by order_channel desc';
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return json_decode(json_encode($res), true);
        }
        return false;
    }

    public static function seventVisit($type = 1) {
        $now = date('Y-m-d', strtotime('-7 days'));
        if ($type == 1) {
            $sql = 'SELECT count(DISTINCT unionid) cnt FROM user_visit_log';
            $sql .= " WHERE date_format(create_time,'%Y-%m-%d')>='$now'";
        } else {
            $sql = 'SELECT count(DISTINCT unionid) cnt FROM user_visit_log';
            $sql .= " WHERE date_format(create_time,'%Y-%m-%d')>='$now'";
            $sql .= " group by date_format(create_time,'%Y-%m-%d') order by create_time desc";
        }
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return json_decode(json_encode($res), true);
        }
        return false;
    }

    public static function userVisit($type = 1) {
        if ($type == 1) {
            $now = date('Y-m-d');
            $sql = 'SELECT count(DISTINCT unionid) cnt FROM user_visit_log';
            $sql .= " WHERE date_format(create_time,'%Y-%m-%d')='$now'";
        } elseif ($type == 2) {
            $now = date('Y-m-d', strtotime('-1 days'));
            $sql = 'SELECT count(DISTINCT unionid) cnt FROM user_visit_log';
            $sql .= " WHERE date_format(create_time,'%Y-%m-%d')>='$now'";
        } else {
            $now = date('Y-m-d', strtotime('-2 days'));
            $sql = 'SELECT count(DISTINCT unionid) cnt FROM user_visit_log';
            $sql .= " WHERE date_format(create_time,'%Y-%m-%d')>='$now'";
        }
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return json_decode(json_encode($res), true)[0];
        }
        return false;
    }

    public static function sevenOrder() {
        $now = date('Y-m-d', strtotime('-7 days'));
        $sql = 'SELECT count(DISTINCT `parent_order_sn`) torders FROM `order`';
        $sql .= " WHERE `status` IN (2,3,5,6) and date_format(create_time,'%Y-%m-%d')>='$now'";
        $sql .= " group by date_format(create_time,'%Y-%m-%d') order by create_time desc";
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return $res;
        }
        return false;
    }

    public static function orderMonth() {
        $now = date('Y-m');
        $sql = "SELECT count(DISTINCT `parent_order_sn`) torders,sum(pay_price) toprice,date_format(create_time,'%Y-%m-%d') totime FROM `order`";
        $sql .= " WHERE `status` IN (2,3,5,6) and date_format(create_time,'%Y-%m')='$now'";
        $sql .= " group by date_format(create_time,'%Y-%m-%d') order by create_time asc";
        $res = self::$dbconn::connection()->select($sql);
        if ($res) {
            return $res;
        }
        return false;
    }
}
