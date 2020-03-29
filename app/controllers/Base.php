<?php
use Yaf\Controller_Abstract;
use Yaf\Registry;
use Yaf\Session;

// use Yaf\Response_Http;

class BaseController extends Controller_Abstract {
    protected $data          = [];
    protected $response_data = ['error_code' => 0, 'message' => '', 'data' => []];
    protected $config;
    protected $session;

    protected function init() {
        $this->config  = Registry::get('config');
        $this->session = Session::getInstance();
        //文件存储目录
        //$file_path       = $this->config['files'];
        //$this->save_path = $file_path['config']['file_path'];
        //redis
        // $r_config = Application::app()->getConfig()->redis->config->toArray();
        // $this->r_host = $r_config['master']['host'];
        // $this->r_port = $r_config['master']['port'];
        $this->setViewPath(APP_PATH . '/views');
    }

}
