<?php
//use Yaf\Session;

class Admin_BaseController extends BaseController {
    public $data    = array();
    protected $rows = 10;
    protected $save_path;
    protected $current_uid;

    public function init() {
        parent::init();
        $username = $this->session->get("username");
        if (!empty($username)) {
            //文件存储目录
            //$this->save_path = $this->config['files']['config']['file_path'];
            $this->data['website_title'] = $this->config['app']['name'];
            //当前用户id
            $this->current_uid = $this->session->get("uid");
            //redis
            //$r_config     = $this->config['redis']['config'];
            //$this->r_host = $r_config['master']['host'];
            //$this->r_port = $r_config['master']['port'];

            $controller                = strtolower($this->getRequest()->getControllerName());
            $menu_uri                  = $controller . '/' . strtolower($this->getRequest()->getActionName());
            $this->data['menu_uri']    = '/' . substr($menu_uri, 6);
            $current_uri               = str_replace("_", "/", $controller);
            $this->data['current_uri'] = substr($current_uri, 5);
            //menu
            $menu_config = new Yaf\Config\Ini(APP_PATH . '/conf/menu.ini');
            if ($menu_config) {
                $menu_arr                = $menu_config->toArray();
                $menu_arr                = Helper::arrayMultirSort($menu_arr, 'sort');
                $this->data['menu_list'] = $menu_arr;
            }

            $this->data['username'] = $username;
            $this->data['profile']  = '/user/profile';
            $this->data['logout']   = '/Admin_Login/Logout';

            //TODO 日志
            $this->getView()->render('/admin/layout/layout.html', $this->data);
        } else {
            //$this->forward('index', array('admin/login', 'index'));
            $this->redirect('/admin_login');
            return false;
        }
    }

    //文件目录
    public function showFileAction() {
        $this->data['sub_cate']  = '文档存储目录';
        $this->data['sub_title'] = 'bis文档库';

        $this->data['files'] = array();
        if (is_dir($this->save_path)) {
            if ($handle = opendir($this->save_path)) {
                chdir($this->save_path);
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        if (is_dir($file)) {
                            $arr = scan_Dir($file);
                            foreach ($arr as $value) {
                                $this->data['files'][] = $value;
                            }
                        } else {
                            $this->data['files'][] = $file;
                        }
                    }
                }
                chdir("../");
            }
            closedir($handle);
        }
        $this->getView()->render('/admin/base/showfile.html', $this->data);
    }

    //文件下载
    public function downloadAction() {
        Yaf_Dispatcher::getInstance()->disableView(0);
        $file_name = $this->getRequest()->getPost('filename');
        $handle    = fopen($this->save_path . '/' . $file_name, "r");
        $file_size = filesize($this->save_path . '/' . $file_name);
        header("Content-type: application/octet-stream");
        header("Content-Type: application/download");
        header("Accept-Ranges: bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Accept-Length:" . $file_size);
        header("Content-Disposition: attachment; filename=" . $file_name);
        echo fread($handle, $file_size);
        fclose($handle);
    }
}
