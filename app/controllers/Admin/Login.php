<?php
use Yaf\Dispatcher;
use Yaf\Registry;
use Yaf\Session;

class Admin_LoginController extends Auth_AuthController {

    protected $config;
    protected $session;

    private $loginView = [
        'SysName'    =>
        ['t1'         => '',
            't2'          => '美到屋管理系统',
            't3'          => '请输入登录信息',
            'fix'         => '',
            'fix_message' => 'Emergency tips',
        ],
        'pageTitle'  => '美到屋后台管理系统登录',
        'to_success' => 'dashboard',
        'to_fail'    => 'login',
    ];

    /**
     * 设置模板引擎路径
     */
    public function init() {
        parent::init();
        $this->config  = Registry::get('config');
        $this->session = Session::getInstance();
    }

    /**
     * [IndexAction  登录页面]
     *
     * @access public
     * @return void
     */
    public function IndexAction() {
        $fix = $this->config['fix'];
        if ($this->session->get("username")) {
            parent::redirect('/dashboard');
        } else {
            $this->loginView['SysName']['fix'] = $fix;
            $data                              = $this->loginView;
            $data['url']                       = '/admin/login/do';
            //$clientIp                          = Tools::getClientIP();
            if (!empty($clientIp)) {
                $this->session->set(CLIENTIP . '_ip', $xsrf);
                $xsrf         = Tools::xsrf();
                $data['xsrf'] = $xsrf;
            }
            $this->getView()->render('/admin/login/index.html', $data);
        }
    }

    /*
     *is_login
     */
    public function doAction() {
        //禁止页面输出
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $username = Tools::getParameter($this->getRequest()->getPost('username'));
            $pwd      = Tools::getParameter($this->getRequest()->getPost('password'));
            $xsrf     = Tools::getParameter($this->getRequest()->getPost('xsrf'));
            if ($username == false || $pwd == false || $xsrf == false) {
                echo Tools::responseResult(0, '用户名或者密码错误');
            }
            if (!$this->session->get(CLIENTIP . '_ip')) {
                echo Tools::responseResult(0, '用户客户端数据错误，请联系客服');
            } else {
                if (trim($this->session->get(CLIENTIP . '_ip')) != $xsrf) {
                    echo Tools::responseResult(0, '用户客户端数据错误，请联系客服');
                }
            }
            //查询user帐号
            $password = md5($pwd . $this->config['salt']);
            $admin    = new AdminModel();
            $where    = [
                'columns'  => 'admin_id as id,account',
                'account'  => $username,
                'password' => $password,
                'status'   => 1,
            ];
            $res = $admin::checkLogin($where);
            if (!empty($res)) {
                if (isset($res['account']) && !empty($res['account'])) {
                    $this->session->set("username", $res['account']);
                    $this->session->set("uid", $res['id']);
                    $this->session->set(CLIENTIP . '_ip', '');
                    echo parent::responseResult(1, '成功');
                } else {
                    echo parent::responseResult(2, '用户信息不存在');
                }
            }
        } else {
            echo parent::responseResult(3, '请求错误');
        }
    }

    /**
     * [LogoutAction  退出登录]
     *
     * @access public
     * @return void
     */
    public function LogoutAction() {
        $this->session->set("username", '');
        header('Location:../admin_login');
    }
}
