<?php
use Yaf\Dispatcher;
use Yaf\Registry;
use Yaf\Session;

class IndexController extends BaseController {

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
        'to_success' => '/admin/dashboard',
        'to_fail'    => '/index',
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
            parent::redirect('/admin/dashboard');
            return false;
        } else {
            $this->loginView['SysName']['fix'] = $fix;
            $data                              = $this->loginView;
            $data['url']                       = '/index/do';
            if (!empty(CLIENTIP)) {
                $xsrf = Helper::xsrf();
                $this->session->set(CLIENTIP . '_ip', $xsrf);
                $data['xsrf'] = $xsrf;
            }
            $this->getView()->render('/index/index.html', $data);
        }
    }

    /*
     *is_login
     */
    public function doAction() {
        //禁止页面输出
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $username = Helper::getParameter($this->getRequest()->getPost('username'));
            $pwd      = Helper::getParameter($this->getRequest()->getPost('password'));
            $xsrf     = Helper::getParameter($this->getRequest()->getPost('xsrf'));
            if ($username == false || $pwd == false || $xsrf == false) {
                echo Helper::responseResult(0, '用户名或者密码错误');
            }
            if (!$this->session->get(CLIENTIP . '_ip')) {
                echo Helper::responseResult(0, '用户客户端数据错误，请联系客服');
            } else {
                if (trim($this->session->get(CLIENTIP . '_ip')) != $xsrf) {
                    echo Helper::responseResult(0, '用户客户端数据错误，请联系客服');
                }
            }
            //查询user帐号
            $password = md5($pwd . $this->config['salt']);
            $admin    = new AdminModel();
            $where    = [
                'account'  => $username,
                'password' => $password,
                'status'   => 1,
            ];
            $res = $admin::getRow($where, ['admin_id', 'account']);
            if (!empty($res)) {
                if (isset($res['account']) && !empty($res['account'])) {
                    $this->session->set("username", $res['account']);
                    $this->session->set("uid", $res['admin_id']);
                    $this->session->set(CLIENTIP . '_ip', '');
                    echo Helper::responseResult(1, '成功');
                } else {
                    echo Helper::responseResult(2, '用户信息不存在');
                }
            }
        } else {
            echo Helper::responseResult(3, '请求错误');
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
