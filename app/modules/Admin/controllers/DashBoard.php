<?php
use Yaf\Dispatcher;

class DashBoardController extends Admin_BaseController {
    /**
     * 设置模板引擎路径
     */
    public function init() {
        parent::init();
    }

    public function IndexAction() {
        $this->data['sub_cate']  = 'DashBoard';
        $this->data['sub_title'] = 'DashBoard';
        $dash                    = new DashModel();
        $today                   = $dash::toDayOrder();
        //过万处理简写为..万
        //if($today['tprice']/10000){
        //}

        $this->data['todayOrders'] = $today['torders'];
        $this->data['todayGMV']    = $today['tprice'];

        $yesterday                     = $dash::toDayOrder(2);
        $this->data['yesterdayGMV']    = $yesterday['tprice'];
        $this->data['yesterdayOrders'] = $yesterday['torders'];

        $todayOrdersIsUP               = bcsub($this->data['todayOrders'], $this->data['yesterdayOrders']);
        $this->data['todayOrdersIsUP'] = false;
        if ($todayOrdersIsUP > 0) {
            $this->data['todayOrdersIsUP'] = true;
        }
        if (!empty($this->data['todayOrders'])) {
            $this->data['todayOrdersPrec'] = bcmul(bcdiv($todayOrdersIsUP, $this->data['todayOrders'], 4), 100);
        } else {
            $this->data['todayOrdersPrec'] = bcmul($todayOrdersIsUP, 100);
        }

        $todayGMVIsUP               = bcsub($this->data['todayGMV'], $this->data['yesterdayGMV']);
        $this->data['todayGMVIsUP'] = false;
        if ($todayGMVIsUP > 0) {
            $this->data['todayGMVIsUP'] = true;
        }
        if (!empty($this->data['todayGMV'])) {
            $this->data['todayGMVPrec'] = bcmul(bcdiv($todayGMVIsUP, $this->data['todayGMV'], 4), 100);
        } else {
            $this->data['todayGMVPrec'] = bcmul($todayGMVIsUP, 100);
        }
        //前天的
        $beforeyesterday  = $dash::toDayOrder(3);
        $byesterdayGMV    = $beforeyesterday['tprice'];
        $byesterdayOrders = $beforeyesterday['torders'];

        $yesterdayOrdersIsUP               = bcsub($this->data['yesterdayOrders'], $byesterdayOrders);
        $this->data['yesterdayOrdersIsUP'] = false;
        if ($yesterdayOrdersIsUP > 0) {
            $this->data['yesterdayOrdersIsUP'] = true;
        }
        if (!empty($this->data['yesterdayOrders'])) {
            $this->data['yesterdayOrdersPrec'] = bcmul(bcdiv($yesterdayOrdersIsUP, $this->data['yesterdayOrders'], 4), 100);
        } else {
            $this->data['yesterdayOrdersPrec'] = bcmul($yesterdayOrdersIsUP, 100);
        }

        $yesterdayGMVIsUP               = bcsub($this->data['yesterdayGMV'], $byesterdayGMV);
        $this->data['yesterdayGMVIsUP'] = false;
        if ($yesterdayGMVIsUP > 0) {
            $this->data['yesterdayGMVIsUP'] = true;
        }
        if (!empty($this->data['yesterdayGMV'])) {
            $this->data['yesterdayGMVPrec'] = bcmul(bcdiv($yesterdayGMVIsUP, $this->data['yesterdayGMV'], 4), 100);
        } else {
            $this->data['yesterdayGMVPrec'] = bcmul($yesterdayGMVIsUP, 100);
        }
        $sevenday                 = $dash::toDayOrder(4);
        $this->data['sevenPrice'] = $sevenday['tprice'];

        $sevenorders                    = $dash::sevenOrder();
        $sevenOrder                     = array_column($sevenorders, 'torders');
        $this->data['sevenOrders']      = implode(',', $sevenOrder);
        $this->data['sevenOrdersTotal'] = array_sum($sevenOrder);

        //TODO
        $visit                 = $dash::seventVisit(1);
        $this->data['sevenUV'] = $visit[0]['cnt'];
        $visit                 = $dash::seventVisit(2);

        $this->data['sevenUVList'] = implode(',', array_column($visit, 'cnt'));

        $todata                  = $dash::toData(1);
        $this->data['topay']     = $todata['torders'];
        $this->data['topay_url'] = '';

        $todata                   = $dash::toData(2);
        $this->data['tosend']     = $todata['torders'];
        $this->data['tosend_url'] = '';

        $todata                     = $dash::toData(3);
        $this->data['tosign']       = $todata['torders'];
        $this->data['tosing_url']   = '';
        $todata                     = $dash::toData(4);
        $this->data['torefund']     = $todata['torders'];
        $this->data['torefund_url'] = '';

        $sku                       = $dash::toProduct(1);
        $this->data['sku_on']      = $sku['sku'];
        $sku                       = $dash::toProduct(2);
        $this->data['sku_off']     = $sku['sku'];
        $sku                       = $dash::toProduct(3);
        $this->data['sku_all']     = $sku['sku'];
        $this->data['product_url'] = '';

        //TODO app 添加uservist 统计代码
        $toDayUV               = $dash::userVisit(1);
        $this->data['todayUV'] = $toDayUV['cnt'];

        $yesterdayUV               = $dash::userVisit(2);
        $this->data['yesterdayUV'] = $yesterdayUV['cnt'];

        $users                          = $dash::toUser(3);
        $this->data['todayaddUser']     = $users['cnt'];
        $this->data['yesterdayaddUser'] = $users['cnt'];

        $users                      = $dash::seventVisit(2);
        $userList                   = array_column($users, 'cnt');
        $this->data['sevenUV']      = implode(',', $userList);
        $this->data['sevenUVTotal'] = array_sum($userList);

        $users                    = $dash::toUser(3);
        $this->data['user_month'] = $users['cnt'];
        $users                    = $dash::toUser(4);
        $this->data['user_all']   = $users['cnt'];

        $this->data['order_channel'] = '/admin/dashboard/orderChannel';
        $this->data['orderList']     = '/admin/dashboard/orderListShow';
        $this->getView()->assign('data', $this->data);
    }

    public function orderChannelAction() {
        Dispatcher::getInstance()->autoRender(0);
        $dash = new DashModel();
        $res  = $dash::toOrderChannel();
        $data = [];
        if ($res) {
            $i = 0;
            foreach ($res as $val) {
                $name = '';
                switch ($val['order_channel']) {
                case 1:
                    $name = 'ios';
                    break;
                case 2:
                    $name = 'android';
                    break;
                case 3:
                    $name = '微信h5';
                    break;
                case 4:
                    $name = '微信min';
                    break;
                case 10:
                    $name = '其他';
                    break;
                }
                if (!empty($name)) {
                    $data[$i]['value'] = $val['cnt'];
                    $data[$i]['name']  = $name;
                    $i++;
                }
            }
        }
        Tools::returnAjaxJson($data);
    }

    public function orderListShowAction() {
        $dash = new DashModel();
        $res  = $dash::orderMonth();
        $data = [];
        if ($res) {
            $data[] = $res;
        }
        Tools::returnAjaxJson($data);
    }

}
