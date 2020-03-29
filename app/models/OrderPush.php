<?php
//use Illuminate\Database\Capsule\Manager as DB;
use sms\ChuanglanSmsApi;
use Yaf\Registry;

class OrderPushModel extends BaseModel {

    private static $postUrl   = '';
    private static $appKey    = '';
    private static $appSecret = '';
    private static $log;
    private static $msg_data = ['name' => 'orderExpressPush'];

    public function __construct() {
        parent::__construct();
        self::$log = new Log();

        $express_config  = Registry::get('express_config');
        self::$postUrl   = $express_config['8i']['postUrl'];
        self::$appKey    = $express_config['8i']['appKey'];
        self::$appSecret = $express_config['8i']['appSecret'];
    }

    /**
     * @doifusd  getOrders 2019-03-06 15:03:37
     * php public/indexcrontab.php request_uri='/ordercron/push'
     * @Return
     */
    public static function getOrders() {
        $product = self::warehouse();
        if (empty($product)) {
            return [];
        }
        $productId = implode(',', array_column($product, 'product_id'));
        $sql       = 'SELECT op.order_express_sn,op.consignee_name,g.name,g.sale_price,g.suit_num,';
        $sql .= 'op.consignee_realname,op.consignee_id,g.product_id,';
        $sql .= 'op.consignee_address,op.consignee_phone,og.goods_id,og.goods_num ';
        $sql .= 'FROM goods g LEFT JOIN order_goods og ON og.goods_id=g.id ';
        $sql .= 'LEFT JOIN order_express op ON op.order_sn=og.order_sn AND og.goods_id=op.goods_id';
        $sql .= ' WHERE g.product_id IN (' . $productId . ') AND op.is_notice=0 AND op.consignee_status = 1';
        $sql .= ' AND op.order_express_sn!=0 AND op.create_time >"2019-03-07 00:00:00"';
        $orderGoods = self::$dbconn::connection()->select($sql);

        if ($orderGoods) {

            foreach ($orderGoods as $val) {
                $data = [];
                if (is_object($val)) {
                    $val = (array) $val;
                }
                $goods_data = [];
                foreach ($product as $value) {
                    if ($value->product_id == $val['product_id']) {
                        array_push($goods_data, ['proTitle' => $val['name'], 'proNo' => $value->product_barcode, 'proSku' => $value->sku_code, 'proPrice' => $val['sale_price'], 'proCount' => $val['goods_num'] * $val['suit_num']]);
                    }
                }
                $data = array(
                    "orderNo"   => $val['order_express_sn'],
                    "UserName"  => $val['consignee_name'],
                    "uName"     => $val['consignee_name'], //收件人姓名[必填]
                    "province"  => $val['consignee_address'], //省份[必填]
                    "city"      => $val['consignee_address'], //城市[必填]
                    "district"  => $val['consignee_address'], //区域[必填]
                    "address"   => $val['consignee_address'], //地址[必填]
                    "postcode"  => "000000", //邮编[必填]
                    "mobiTel"   => $val['consignee_phone'], //手机号码[可填][注：手机号码和电话号码至少填一项]
                    "phone"     => $val['consignee_phone'], //电话号码[可填][注：手机号码和电话号码至少填一项]
                    "cRemark"   => "", //买家留言[可填]
                    "oRemark"   => "", //卖家备注[可填]
                    "oSumPrice" => "0", //实付订单总金额[必填]
                    "expFee"    => "0", //实付订单运费[可填][默认为0]
                    "expCod"    => "0", //是否货到付款[必填][1：货到付款]
                    "codFee"    => "0", //货到付款手续费[可填][默认为0]
                    "expCodFee" => "0", //货到付款代收运费[可填][默认为0]
                    "Card"      => $val['consignee_id'],
                    "RealName"  => $val['consignee_realname'],
                    "OrderPro"  => str_replace('&', '', json_encode($goods_data))); //货到付款代收运费[可填][默认为0]
                $datas = self::paramLayout($data);
                $param = [
                    'method' => 'IOpenAPI.AddOrder',
                    'data'   => $datas,
                ];
                $res = self::requestApi($param);
                if ($res['Code'] == 101) {
                    $where = [
                        'is_notice'        => 0,
                        'order_express_sn' => $val['order_express_sn'],
                    ];
                    $updata = ['is_notice' => 1, 'express_third' => $res['Result']];
                    self::$dbconn::connection()->beginTransaction();
                    $sqlres = self::$dbconn::connection()->table('order_express')
                        ->where($where)->update($updata);
                    if ($sqlres) {
                        self::$dbconn::connection()->commit();
                        $msg = 'update success order_express_sn: ' . $val['order_express_sn'];
                    } else {
                        self::$dbconn::connection()->rollback();
                        //add log
                        $msg = 'update fail order_express_sn: ' . $val['order_express_sn'];
                    }
                    self::$msg_data['content'] = $msg;
                    self::$log->write(self::$msg_data);
                } else {
                    //add log
                    self::$msg_data['content'] = 'ba ai response code fail: ';
                    self::$log->write(self::$msg_data);
                }
            }
        }
    }

    /**
     * 查询订单物流 更新入表
     *
     * @Return
     */
    public static function getLogistics() {
        $product = self::warehouse();
        if (empty($product)) {
            return [];
        }
        $productId = implode(',', array_column($product, 'product_id'));
        //查询已通知未获得物流编号的订单
        $sql = 'SELECT op.order_express_sn,op.order_sn,op.consignee_phone,';
        $sql .= 'g.name,g.product_id,';
        $sql .= 'og.goods_id,og.goods_num ';
        $sql .= 'FROM goods g LEFT JOIN order_goods og ON og.goods_id=g.id ';
        $sql .= 'LEFT JOIN order_express op ON op.order_sn=og.order_sn AND og.goods_id=op.goods_id';
        $sql .= ' WHERE g.product_id IN (' . $productId . ') AND op.is_notice=1 ';
        $sql .= ' AND op.order_express_sn!="" AND op.consignee_status = 1 AND op.express_sn="" AND op.create_time >"2019-03-07 00:00:00"';

        $orderGoods = self::$dbconn::connection()->select($sql);

        $express_info_sql = 'SELECT express_name,name FROM express_info ';
        $express_info     = self::$dbconn::connection()->select($express_info_sql);
        
        if ($orderGoods) {
            foreach ($orderGoods as $key => $val) {
                $data = [];
                if (is_object($val)) {
                    $val = (array) $val;
                }

                //查询该物流单是否还存在未发货
                $if_has_no_ex_sql = "SELECT id FROM order_express WHERE order_express_sn='" . $val['order_express_sn'] . "' AND consignee_status = 1";
                $if_has_no_ex     = self::$dbconn::connection()->select($if_has_no_ex_sql);
                if (!$if_has_no_ex) {
                    continue;
                }
                foreach ($product as $value) {
                    if ($value->product_id == $val['product_id']) {
                        $proSkuNo = $value->sku_code;
                    }
                }
                $data = [
                    "orderId"  => "",
                    "orderNo"  => $val['order_express_sn'],
                    "billCode" => "",
                    "proSkuNo" => $proSkuNo,
                ];
                $datas = self::paramLayout($data);
                $param = [
                    'method' => 'IOpenAPI.GetOrderDeliver',
                    'data'   => $datas,
                ];
                $res = self::requestApi($param);
                if ($res['Code'] == 101 && !empty($res['Result'])) {
                    $express_sn = "";
                    foreach ($res['Result'] as $k1 => $v1) {
                        if ($express_sn != $v1['ExpNum']) {
                            $is_sms_push = 1;
                        }
                        $express_sn   = $v1['ExpNum'];
                        $express_name = $v1['Sel_Exp_Name'];
                        $BillNo       = $v1['BillNo']; //出库单号
                        if (!empty($express_name)) {
                            foreach ($express_info as $k => $v) {
                                if (is_object($v)) {
                                    $v = (array) $v;
                                }
                                if (mb_strstr($express_name, $v['name']) !== false) {
                                    $express_name     = $v['express_name'];
                                    $sms_express_name = $v['name'];
                                    break;
                                }
                            }
                        }
                        $product_id_sql = "SELECT product_id FROM warehouse_product WHERE sku_code = " . $v1['SkuNo'] . " AND status = 1 LIMIT 1";
                        $product_id     = self::$dbconn::connection()->select($product_id_sql)[0];
                        if (is_object($product_id)) {
                            $product_id = (array) $product_id;
                        }
                        $goods_id_sql   = "SELECT id FROM goods WHERE product_id = " . $product_id['product_id'] . " LIMIT 1";
                        $goods_id       = self::$dbconn::connection()->select($goods_id_sql)[0];
                        if (is_object($goods_id)) {
                            $goods_id = (array) $goods_id;
                        }
                        $where          = [
                            'order_express_sn' => $val['order_express_sn'],
                            'goods_id'         => $goods_id['id'],
                        ];
                        $updata = [
                            'express_sn'       => $express_sn,
                            'express_name'     => $express_name,
                            'express_info'     => json_encode(["BillNo" => $BillNo]),
                            'consignee_status' => 3, //已发货
                            'update_time'      => date("Y-m-d"),
                        ];
                        $where_order = [
                            'order_sn' => $val['order_sn'],
                        ];
                        $updata_order = [
                            'status' => 3,
                        ];
                        self::$dbconn::connection()->beginTransaction();
                        $sqlres = self::$dbconn::connection()->table('order_express')
                            ->where($where)->update($updata);
                        self::$dbconn::connection()->table('order')
                            ->where($where_order)->update($updata_order);

                        if ($sqlres) {
                            self::$dbconn::connection()->commit();
                            $msg = 'send success and update express_sn success order_express_sn: ' . $val['order_express_sn'] . '(express_sn:' . $express_sn . ';express_name:' . $express_name . ')';
                        } else {
                            self::$dbconn::connection()->rollback();
                            //add log
                            $msg = 'send success and update express_sn fail order_express_sn: ' . $val['order_express_sn'];
                        }

                        self::$msg_data['content'] = $msg;
                        self::$log->write(self::$msg_data);
                        if ($is_sms_push == 1) {
                            $clapi     = new ChuanglanSmsApi();
                            $count_sql = 'SELECT count(id) FROM order_express WHERE order_express_sn = ' . $val['order_express_sn'] . ' AND express_sn=' . $v1['ExpNum'];
                            $count     = self::$dbconn::connection()->select($count_sql);
                            if ($count > 1) {
                                $msg_con = '【美到屋】尊敬的用户，您在美到屋商城订购的' . $val['name'] . '等商品已发货，订单编号（' . $val['order_sn'] . '）' . $sms_express_name . '快递' . $express_sn . '，请注意查收。';
                            } else {
                                $msg_con = '【美到屋】尊敬的用户，您在美到屋商城订购的' . $val['name'] . '商品已发货，订单编号（' . $val['order_sn'] . '）' . $sms_express_name . '快递' . $express_sn . '，请注意查收。';
                            }
                            $result = $clapi->sendSMS($val['consignee_phone'], $msg_con);
                        }
                    }
                } else {
                    //add log
                    $msg                       = 'IOpenAPI.GetOrder fail code: ' . json_encode($res);
                    self::$msg_data['content'] = $msg;
                    self::$log->write(self::$msg_data);
                }
            }
        }

    }

    //已支付订单半个小时内取消 通知供货商取消订单 (未发货订单)
    public static function cancelOrder() {

        $product = self::warehouse();
        if (empty($product)) {
            return [];
        }
        $productId = implode(',', array_column($product, 'product_id'));

        $sql = 'SELECT op.order_express_sn,op.express_third,op.order_sn,';
        $sql .= 'g.name,g.product_id ';
        $sql .= 'FROM goods g LEFT JOIN order_goods og ON og.goods_id=g.id ';
        $sql .= 'LEFT JOIN order_express op ON op.order_sn=og.order_sn AND og.goods_id=op.goods_id';
        $sql .= ' WHERE g.product_id IN (' . $productId . ') AND op.is_notice=1 AND op.consignee_status = 8';
        $sql .= ' AND op.order_express_sn!=0 AND op.ext != "8i订单推送取消" AND op.create_time >"2019-03-07 00:00:00"';

        $orderGoods = self::$dbconn::connection()->select($sql);

        if ($orderGoods) {
            foreach ($orderGoods as $val) {
                $data = [];
                if (is_object($val)) {
                    $val = (array) $val;
                }
                $data = [
                    "orderId"      => $val['express_third'],
                    "cancelReason" => "用户取消订单",
                ];
                $datas = self::paramLayout($data);
                $param = [
                    'method' => 'IOpenAPI.CancelOrderState',
                    'data'   => $datas,
                ];
                $res = self::requestApi($param);
                if ($res['Code'] == 101) {
                    $where   = [
                        'order_express_sn' => $val['order_express_sn'],
                    ];
                    $updata = [
                        'ext' => '8i订单推送取消',
                    ];
                    self::$dbconn::connection()->beginTransaction();
                    $res = self::$dbconn::connection()->table('order_express')
                        ->where($where)->update($updata);
                    if ($res) {
                        self::$dbconn::connection()->commit();
                        $msg = 'send success and cancelorder success order_express_sn: ' . $val['order_express_sn'];
                    } else {
                        self::$dbconn::connection()->rollback();
                        //add log
                        $msg = 'send success and cancelorder fail order_express_sn: ' . $val['order_express_sn'];
                    }
                    self::$msg_data['content'] = $msg;
                    self::$log->write(self::$msg_data);
                } else {
                    //add log
                    $msg                       = 'IOpenAPI.GetOrder fail code: ' . $res['Code'];
                    self::$msg_data['content'] = $msg;
                    self::$log->write(self::$msg_data);
                }
            }
        }
    }

    //查询商品库存信息
    public static function getProductSku() {
        $product = self::warehouse();
        if (empty($product)) {
            return [];
        }

        $sku_code = implode(',', array_column($product, 'sku_code'));
        $array    = [
            "proSkuNo" => $sku_code,
        ];
        $data  = self::paramLayout($array);
        $param = [
            'method' => 'IOpenAPI.GetProductSkuInfo',
            'data'   => $data,
        ];
        $res = self::requestApi($param);
        if ($res['Code'] == "101" && !empty($res['Result'])) {
            foreach ($res['Result'] as $key => $val) {
                self::$dbconn::connection()->beginTransaction();
                $sql    = 'UPDATE warehouse_product SET totalstock=' . $val['ProCount'] . ' WHERE sku_code="' . $val['ProSkuNo'] . '" AND status = 1 LIMIT 1';
                
                $product_id_sql = "SELECT product_id FROM warehouse_product WHERE sku_code = '" . $val['ProSkuNo']  . "' LIMIT 1";
                $product_id     = self::$dbconn::connection()->select($product_id_sql)[0];
                
                if (is_object($product_id)) {
                    $product_id = (array) $product_id;
                }
                $goodssql    = 'UPDATE goods SET stock=' . $val['ProCount'] . ' WHERE product_id="' . $product_id['product_id'] . '" LIMIT 1';
                
                $sqlres = self::$dbconn::connection()->update($sql);
                $goodssqlres = self::$dbconn::connection()->update($goodssql);
                if ($sqlres && $goodssqlres) {
                    self::$dbconn::connection()->commit();
                    $msg = 'send success and totalstock update success sku_code: ' . $val['ProSkuNo'];
                } else {
                    self::$dbconn::connection()->rollback();
                    //add log
                    $msg = 'send success and totalstock update fail sku_code: ' . $val['ProSkuNo'];
                }
                self::$msg_data['content'] = $msg;
                self::$log->write(self::$msg_data);
            }
        } else {
            $msg                       = 'IOpenAPI.GetProductSkuInfo.code' . $res['Code'] . "mag:" . $res['Message'];
            self::$msg_data['content'] = $msg;
            self::$log->write(self::$msg_data);
        }
    }

    public static function warehouse(): array{
        $sql     = 'select id from warehouse_supplier where id = 14';
        $suplier = self::$dbconn::connection()->select($sql);
        if ($suplier) {
            $suplierIdArr = implode(',', array_column($suplier, 'id'));
            $sql          = 'select id from warehouse where warehouse_sup_id in (';
            $sql         .= $suplierIdArr . ')';
            $warehouse = self::$dbconn::connection()->select($sql);
            if ($warehouse) {
                $whIdArr = implode(',', array_column($warehouse, 'id'));
                $sql     = 'select product_id,sku_code,product_barcode from ';
                $sql .= 'warehouse_product where warehouse_id in (';
                $sql .= $whIdArr . ') AND sku_code!="" AND status = 1';
                $product = self::$dbconn::connection()->select($sql);
                if ($product) {
                    return $product;
                } else {
                    return [];
                }
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public static function paramLayout($array) {
        $data                    = array();
        $data['post_data_filed'] = "";
        $data['postData']        = "";
        foreach ($array as $key => $value) {
            $data['post_data_filed'] .= "&" . $key . "=" . $value;
            $data['postData'] .= $key . $value;
        }
        return $data;
    }

    public static function requestApi($param) {
        $apiFormat       = "json";
        $apiMethod       = $param['method'];
        $data            = $param['data']; //返回加密数据格式(请求参数)
        $post_data_filed = $data['post_data_filed'];
        $postData        = $data['postData'];

        $inputStr = strtolower(str_replace(" ", '', self::$appSecret . $apiMethod . 'appKey' . self::$appKey . $postData)); //转小写
        $sort     = self::mbstringtoarray($inputStr, 'utf-8');
        sort($sort, SORT_STRING);
        $str = "";
        foreach ($sort as $val) {
            $str .= $val;
        }
        $paramStr = $str;
        $sToken   = md5($paramStr);
        $postData = 'user=' . self::$appKey . '&method=' . $apiMethod . '&token=' . $sToken . '&format=' . $apiFormat . '&appKey=' . self::$appKey . $post_data_filed;
        $res      = self::curlPost(self::$postUrl, $postData);
        return json_decode($res, true);
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
