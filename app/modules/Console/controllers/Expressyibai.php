<?php
use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

class ExpressyibaiController extends Controller_Abstract {

    public function init() {
        Dispatcher::getInstance()->disableView(0);
        //Dispatcher::getInstance()->autoRender(0);
    }
    /*
     * 快递100订阅订单物流信息
     * php public/indexcrontab.php request_uri='/console/expressyibai/subscribe'
     */
    public function subscribeAction() {
        $expressyibai = new ExpressYiBaiModel();
        $expressyibai::subscribeExpress();
    }
}
