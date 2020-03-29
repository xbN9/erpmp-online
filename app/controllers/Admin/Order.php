<?php

/**
 * Class Admin_FlowController
 */
class Admin_OrderController extends Yaf_Controller_Abstract
{

	/**
	 * 设置模板引擎路径
	 */
	public function init(){
//		if(Yaf_Session::getInstance()->get("username")){
//			$this->setViewPath(APP_PATH . '/views');
//		}else{
//			$this->redirect('/admin_login');
//		}
		$this->setViewPath(APP_PATH . '/views');
	}

	/**
	 * order
	 */
	public function indexAction(){
		$data = array('nav_name'=>'订单统计','nav_sub_name'=>'实时统计');
//		$orders = new OrdersModel();
//		$res = $orders->get_orders('pc','criteo');
//		die;
		$this->getView()->render('/admin/order/index.html', $data);

	}

	public function testAction(){
		$s = array(
			"Unknown"=>3,
			"湖北省"=>2
		);

		$data['a'] = json_encode($s);
		$this->getView()->render('/admin/order/index.html', $data);
	}
	/**
	 * 实时订单数据流
	 */
	public function orderDataAction(){
		Yaf_Dispatcher::getInstance()->autoRender(FALSE);
		$orders = new OrdersModel();
		$res = $orders->get_orders('pc','criteo');
		echo $res;
	}

}
