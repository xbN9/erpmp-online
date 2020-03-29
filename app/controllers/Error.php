<?php
use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

class ErrorController extends Controller_Abstract {

    //private $_config;

    public function init() {
        //$this->_config = Application::app()->getConfig();
        $this->setViewPath(APP_PATH . '/views');
        if (!DEBUG) {
            header('Location:/');
            Dispatcher::getInstance()->disableView();
        }
    }

    //TODO 错误字典对应处理
    public function errorAction($exception) {
        $error = '';
        switch ($exception->getCode()) {
        // case YAF_ERR_NOTFOUND_MODULE:
        // case YAF_ERR_NOTFOUND_CONTROLLER:
        // case YAF_ERR_NOTFOUND_ACTION:
        // case YAF_ERR_NOTFOUND_VIEW:
        // $error = '404:'.$exception->getMessage();
        // break;
        default:
            $error = ":" . $exception->getMessage();
            break;
        }

        $this->getView()->render('/error/error.html', array('error' => $error, 'year' => date('Y', time())));
        //Yaf的错误代码常量, 表示启动失败, 值为512
        //Yaf的错误代码常量, 表示路由失败, 值为513
        //Yaf的错误代码常量, 表示分发失败, 值为514
        //Yaf的错误代码常量, 表示找不到指定的模块, 值为515
        //Yaf的错误代码常量, 表示找不到指定的Controller, 值为516
        //Yaf的错误代码常量, 表示找不到指定的Action, 值为517
        //Yaf的错误代码常量, 表示找不到指定的视图文件, 值为518
        //Yaf的错误代码常量, 表示调用失败, 值为519
        //Yaf的错误代码常量, 表示自动加载类失败, 值为520
        //Yaf的错误代码常量, 表示关键逻辑的参数错误, 值为521
        //         switch ($exception->getCode()) {
        //             case 512:
        //             case YAF_ERR_NOTFOUND_CONTROLLER:
        //             case YAF_ERR_NOTFOUND_ACTION:
        //             case YAF_ERR_NOTFOUND_VIEW:
        //                 $this->getView()->assign('code',$exception->getCode());
        //                 //$this->getView()->assign('msg', $exception->getMessage());
        //                 $this->getView()->render('error', array('msg'=>$exception->getMessage()));
        //
        //                 break;
        //             default :
        //                 $this->getView()->assign('code',$exception->getCode());
        //                 $this->getView()->assign('msg', $exception->getMessage());
        //                 break;
        //         }
    }
}
