<?php
use Yaf\Dispatcher;

class Admin_TagController extends Admin_BaseController {
    /**
     * 设置模板引擎路径
     */
    public function init() {
        parent::init();
    }

    public function listAction() {
    }

    public function listDataAction() {
        Dispatcher::getInstance()->autoRender(0);
    }

    public function addAction() {
        Dispatcher::getInstance()->autoRender(0);
    }

    public function editAction() {
        Dispatcher::getInstance()->autoRender(0);
    }

}
