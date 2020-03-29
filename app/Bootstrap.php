<?php
use Yaf\Application;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Loader;
use Yaf\Registry;
use Yaf\Session;

class Bootstrap extends Bootstrap_Abstract {
    private $config;

    public function _initConfig(Dispatcher $dispatcher) {
        //主机协议
        define('SITE_PROTOCOL', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://');
        //当前访问的主机名
        define('SITE_URL', (isset($_SERVER['HTTP_HOST']) ? SITE_PROTOCOL . $_SERVER['HTTP_HOST'] : ''));
        //来源
        define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
        //系统开始时间
        define('SYS_START_TIME', microtime());
        //定义字符集
        define('CHARSET', 'utf8');
        //输出页面字符集
        header('Content-type: text/html; charset=' . CHARSET);
        define('LIBRARY', APP_PATH . "/../library");
        // define("EDITOR_HOME", APP_PATH."/../library/ueditor");
        define('EDITOR_HOME', SITE_URL . "/ueditor");
        define('CLIENTIP', Helper::getRemoteAddr());
        //请求方法
        define('REQUEST_METHOD', $dispatcher->getRequest()->method);
        $this->config = Application::app()->getConfig()->toArray();
        Registry::set('config', $this->config);
    }

    public function _initSession(Dispatcher $dispatcher) {
        Session::getInstance()->start();
    }

    /**
     * [_initIncludePath  description]
     * @access public
     * @return void
     */
    public function _initIncludePath() {
        set_include_path(get_include_path() . PATH_SEPARATOR . $this->config['application']['library']);
    }

    public function _initErrors() {
        if (REQUEST_METHOD != 'Cli') {
            if ($this->config['application']['showErrors']) {
                error_reporting(E_ALL);
            }
        }
        // $app->getDispatcher()->setErrorHandler("error_handler", E_RECOVERABLE_ERROR);
    }

    public function _initNamespaces() {
        //Loader::getInstance()->registerLocalNameSpace(['Tool']);
        //Loader::getInstance()->registerLocalNameSpace(['Entity']);
    }

    public function _initRoutes(Dispatcher $dispatcher) {
        $config = new Yaf\Config\ini(APP_PATH . '/conf/route.ini', 'common');
        if ($config->routes) {
            $router = $dispatcher->getRouter();
            $router->addConfig($config->routes);
        }
    }

    /**
     * [_initAutoload  注册composer]
     *
     * @access public
     * @return void
     */
    public function _initAutoload() {
        Loader::import(APP_PATH . '/../library/vendor/autoload.php');
    }

    public function _initMinify() {
        // Loader::import(APP_PATH . '/../library/vendor/mrclay/minify/index.php');
    }

    /**
     * [_initTwig  模板引擎]
     * @param [Dispatcher] $dispatcher [description]
     * @access public
     * @return void
     */
    public function _initTwig(Dispatcher $dispatcher) {
        //if (REQUEST_METHOD != 'Cli') {
        $twig = new TwigAdapter();
        $dispatcher->setView($twig);
        //}
    }

    /**
     * [_initDBAdapter  数据初始化]
     * @access public
     * @return void
     */
    public function _initDBAdapter() {
        $config = new Yaf\Config\Ini(APP_PATH . '/conf/database.ini');
        $config = $config->toArray();
        Registry::set('db_config', $config);
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($config['mysql']);
        $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container));
        $capsule->setAsGlobal();
        //开启Eloquent ORM
        $capsule->bootEloquent();
        Registry::set('DB', '\Illuminate\Database\Capsule\Manager');
    }

    public function _initSms(Dispatcher $dispatcher) {
        $config = new Yaf\Config\Ini(APP_PATH . '/conf/sms.ini');
        $config = $config->toArray();
        Registry::set('sms_config', $config);
    }

    public function _initExpress(Dispatcher $dispatcher) {
        $config = new Yaf\Config\Ini(APP_PATH . '/conf/express.ini');
        $config = $config->toArray();
        Registry::set('express_config', $config);
    }

    /**
     *mongo
     */
    // public function _initDefaultMongoAdapter(){
    //     $mongo = BaseMongo::getInstance();
    //     Yaf_Registry::set('mongoDB', $mongo);
    // }

    /**
     * link memcache
    public function _initMemcache(){
    $mem_host = $this->_config->get("memcache")->params->host;
    $mem_port = $this->_config->get("memcache")->params->port;
    $mem_name =  $this->_config->get("memcache")->params->name;
    $mem = new Memcached($mem_name);
    $mem->addServer($mem_host, $mem_port);
    Yaf_Registry::set('mem', $mem);
    }*/

    // @param Yaf_Dispatcher $dispatcher
    public function _initXHProf(Dispatcher $dispatcher) {
        if (isset($this->config['xhprof']) && ini_get('yaf.environ') == 'devel') {
            if (extension_loaded('xhprof') && isset($this->config['xhprof']['open']) && !empty($this->config['xhprof']['open'])) {
                $default_flags    = 'XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY';
                $ignore_functions = (isset($this->config['xhprof']['ignored_functions']) && is_array($this->config['xhprof']['ignored_functions'])) ? $this->config['xhprof']['ignored_functions'] : array();
                if (isset($this->config['xhprof']['flags'])) {
                    xhprof_enable($this->config['xhprof']['flags'], array('ignored_functions' => $ignore_functions));
                } else {
                    xhprof_enable($default_flags, array('ignored_functions' => $ignore_functions));
                }
            }
        }
    }

    /**
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initPlugin(Dispatcher $dispatcher) {
        $dispatcher->registerPlugin(new XHProfPlugin());
        //cookie涉及HTTP请求，命令行下应禁用
        // if (REQUEST_METHOD != 'cli')
        // {
        //     // $cookie = new CookiePlugin();
        //     // $dispatcher->registerPlugin($cookie);
        // }
    }

    /*public function _initMailer(Yaf_Dispatcher $dispatcher) {
    if (isset($this->config->smtp))
    {
    Yaf_Loader::import(APPLICATION_PATH . '/library/PHPMailer/PHPMailer.php');
    }
    }*/

    /**
     * 公用函数载入
    public function _initFunction()
    {
    \Yaf\Loader::import('Common/functions.php');
    }
     */
}
