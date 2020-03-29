<?php
use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

class OrdercronController extends Controller_Abstract {

    public function init() {
        Dispatcher::getInstance()->disableView(0);
        //Dispatcher::getInstance()->autoRender(0);
    }

    /**
     * @doifusd  pushAction 2019-03-07 14:17:50
     * php public/indexcrontab.php request_uri='/console/ordercron/push'
     * @Return
     */
    public function pushAction() {
        $order = new OrderPushModel();
        $order::getOrders();
    }

    /**
     * @doifusd  testAction 2019-03-07 14:50:22
     * bin php public/indexcrontab.php request_uri='/console/ordercron/test'
     * @Return
     */
    public function testAction() {
        echo 'this is test';
        die;
    }

    /*
     * 查询八爱系统供货商商品订单物流信息
     * php public/indexcrontab.php request_uri='/console/ordercron/logistics'
     */
    public function logisticsAction() {
        $order = new OrderPushModel();
        $today = $order::getLogistics();
    }

    /*
     * 取消订单
     * php public/indexcrontab.php request_uri='/console/ordercron/cancel'
     */
    public function cancelAction() {
        $order = new OrderPushModel();
        $today = $order::cancelOrder();
    }
    /*
     * 查询供货商商品库存
     * php public/indexcrontab.php request_uri='/console/ordercron/sku'
     */
    public function skuAction() {
//        error_reporting(E_ALL);
//        // 是否开启错误日志记录 将错误记录至指定文件
//        ini_set('log_errors', true);
//        ini_set('error_log', '/var/log/phalcon/testsku.txt');
        
        $order = new OrderPushModel();
        $today = $order::getProductSku();
    }
}
