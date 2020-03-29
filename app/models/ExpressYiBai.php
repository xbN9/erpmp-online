<?php

class ExpressYiBaiModel extends BaseModel {

    private static $postUrl   = 'http://www.kuaidi100.com/poll';
    private static $key    = 'SNsBopjm710';
    private static $callbackurl = "https://erp.meidaowu.com/admin-api/courier100_callback";
    private static $log;
    private static $msg_data = ['name' => 'courier100_callback'];

    public function __construct() {
        parent::__construct();
        //self::$log     = new Logger('log');
        //self::$logFile = '/var/log/phalcon/' . date('Y-m-d') . '_orderExpressPush.log';
        //self::$log->pushHandler(new StreamHandler(self::$logFile, Logger::WARNING));
        self::$log = new Log();
    }
    
    /*
     * 订阅订单物流情况
     */
    public static function subscribeExpress() {
        //查询未订阅的物流单号
        $sql = "SELECT order_express_sn,express_name,express_sn FROM order_express WHERE is_subscribe = 0 AND express_name != '' AND  express_sn != '' ";
        $order_express_list = self::$dbconn::connection()->select($sql);
//        var_dump($order_express_list);
        if (!empty($order_express_list)) {
            foreach ($order_express_list as $key => $val) {
                $post_data = array();
                $post_data["schema"] = 'json';

                //callbackurl请参考callback.php实现，key经常会变，请与快递100联系获取最新key
                $post_data["param"] = '{"company":"' . $val['express_name'] . '", "number":"' . $val['express_sn'] . '",';
                $post_data["param"] = $post_data["param"] . '"key":"'.self::$key.'",';
                $post_data["param"] = $post_data["param"] . '"parameters":{"callbackurl":"'.self::$callbackurl.'"}}';

                $url = self::$postUrl;

                $o = "";
                foreach ($post_data as $k => $v) {
                    $o.= "$k=" . urlencode($v) . "&";  //默认UTF-8编码格式
                }
                $post_data = substr($o, 0, -1);
                $result = json_decode(curl_post($url, $post_data), true);
                if (empty($result)) {
                    $ret = date("Y-m-d H:i:s") . ":快递单号_" . $val['express_sn'] . ":订阅失败\r\n";
                    add_log($ret);
                } else {
                    if ($result['returnCode'] == 200) {
                        Flight::db()->update('order_express', ['is_subscribe' => 1], ['AND' => ['order_express_sn' => $val['order_express_sn'],'express_sn'=>$val['express_sn']]]);
                        $msg = "快递单号_" . $val['express_sn'] . ":发送成功" . json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    } elseif ($result['returnCode'] == 501) {
                        Flight::db()->update('order_express', ['is_subscribe' => 1], ['AND' => ['order_express_sn' => $val['order_express_sn'],'express_sn'=>$val['express_sn']]]);
                        $msg = "快递单号_" . $val['express_sn'] . ":重复订阅" . json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    } else {
                        $msg = "快递单号_" . $val['express_sn'] . ":其他情况" . json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }
                    self::$msg_data['content'] = $msg;
                    self::$log->write(self::$msg_data);
                }
            }
        }
    }

    public static function curlPost($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public static function mbstringtoarray($str, $charset) {
        $strlen = mb_strlen($str);
        while ($strlen) {
            $array[] = mb_substr($str, 0, 1, $charset);
            $str     = mb_substr($str, 1, $strlen, $charset);
            $strlen  = mb_strlen($str);
        }
        return $array;
    }
}
