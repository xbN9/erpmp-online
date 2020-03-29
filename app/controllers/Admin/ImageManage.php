<?php
use Yaf\Session;
use Yaf\Dispatcher;
use Yaf\Registry;

// use Doctrine\ORM\Mapping as ORM;
// use Doctrine\ORM\Tools\Pagination\Paginator;

class Admin_ImageManageController extends Admin_BaseController
{
    public $tmp = array();
    public $img_list = [];
    public $rows = 5;
    public function init()
    {
        parent::init();
        $this->data['sub_cate'] = '图片管理';
        //获取当前请求地址
    }

    //列表,预览
    //批量水印
    //批量压缩
    public function listAction()
    {
        $this->data['module_name'] = '图片管理';
        $this->data['sub_title'] = '图片列表';
        $this->data['tab_sub_title'] = '图片列表';
        $this->data['data_title'] = array('名字', '缩略', '操作');

        $this->data['sAjaxSource'] = '/admin/imageManage/listData';
        $this->data['changeurl'] = '/admin/imageManage/change?id=';
        $this->data['delurl'] = '/admin/imageManage/del';
        $this->data['rows'] = $this->rows;
        $this->getView()->assign('data', $this->data);
    }

    public function listDataAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        // 获取图片地址
        $path = PUBLIC_PATH.'/data/image';
        $this->getImageList($path);
        array_multisort(array_column($this->img_list, 'name'), SORT_DESC, $this->img_list);
        $this->img_list = array_values($this->img_list);
        $start = $this->getRequest()->get('iDisplayStart');
        $res =  array_slice($this->img_list, $start, $this->rows);
        $total_rows = count($this->img_list);
        $tmp['iTotalRecords'] = $total_rows; //返回行数
        $tmp['iTotalDisplayRecords'] = $total_rows; //过滤后的行数
        $tmp['data'] = $res;
        echo json_encode($tmp);
    }
    
    public function delAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $id = trim($this->getRequest()->getPost('id'));
            if (!empty($id)) {
                if (is_file($id)) {
                    if (@unlink($id)) {
                        echo $this->responseResult('1', 'success');
                    } else {
                        echo $this->responseResult('0', 'del file fail');
                    }
                } else {
                    echo $this->responseResult('0', 'file not found');
                }
            } else {
                echo $this->responseResult('0', 'id not found');
            }
        } else {
            echo $this->responseResult('0', 'not post');
        }
    }

    protected function getImageList($path)
    {
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file!='.' && $file!='..' && $file[0]!='.') {
                        $file_path = $path.'/'.$file;
                        if (is_file($file_path)) {
                             $tmp = [
                                 'name'=>$file,
                                 'file'=>$file_path,
                                 'show'=>substr($file_path, (strrpos($file_path, 'public')+strlen('public')))];
                             array_push($this->img_list, $tmp);
                        } elseif (is_dir($file_path)) {
                             $this->getImageList($file_path);
                        }
                    }
                }
                closedir($dh);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function changeAction()
    {
        $this->data['sub_title'] = '图片管理';
        $id = trim($this->getRequest()->get('id'));
        if (!empty($id)) {
            if (!is_file($id)) {
                $this->redirect('/admin/imageManage/list');
            }
        } else {
            $this->redirect('/admin/imageManage/list');
        }
        $this->data['formaction'] = '/admin/imageManage/changeDo';
        $this->data['formrefer'] = '/admin/imageManage/list';
        $this->data['img_src'] = $id;
        $this->data['img_show'] = substr($id, (strrpos($id, 'public')+strlen('public')));
        $imgInfo = getimagesize($id);
        $this->data['img_width'] = $imgInfo[0];
        $this->data['img_height'] = $imgInfo[1];
        $this->getView()->assign('data', $this->data);
    }

    public function changeDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $arr_params = $this->getRequest()->getPost();
            if (!empty($arr_params)) {
                $img_id = trim($arr_params['id']);
                if (!isset($img_id)) {
                    echo $this->responseResult(0, 'not img');
                    exit(1);
                }
                if (empty($arr_params['width']) || empty($arr_params['height'])) {
                    echo $this->responseResult(0, 'not cut img');
                    exit(1);
                }
                $arr_img = [];
                $arr_img['width'] = intval($arr_params['width']);
                $arr_img['height'] = intval($arr_params['height']);
                $arr_img['x'] = $arr_params['x'];
                $arr_img['y'] = $arr_params['y'];
                $arr_img['rotate'] = $arr_params['rotate'];
                $arr_img['scale_x'] = $arr_params['scaleX'];
                $arr_img['scale_y'] = $arr_params['scaleY'];

                $image = new Image($img_id);
                $file = $image->createTinyImage($arr_img);
                if ($image->waterMarkImage($file, true)) {
                    echo $this->responseResult(1, 'create success');
                    exit(0);
                } else {
                    echo $this->responseResult(0, 'create fail');
                    exit(1);
                }
            } else {
                echo $this->responseResult(0, 'not data');
                exit(1);
            }
        } else {
            echo $this->responseResult(0, 'not post');
            exit(1);
        }
    }

    /*
    public function addAction()
    {
        $this->data['module_name'] = '后台菜单管理';
        $this->data['sub_title'] = '创建菜单';

        $sql = 'SELECT p.name,p.Id FROM MenuModel p where p.parentId=0';
        $query = parent::$db_resouce->createQuery($sql);
        $parentList = $query->getArrayResult();
        if ($parentList && !empty($parentList)) {
            $this->data['parentArr']=$parentList;
        }
        // $this->iconList();
        $this->data['formaction'] = '/admin/menu/addMenuDo';
        $this->data['formmethod'] = 'post';
        $this->data['formrefer'] = '/admin/menu/list';
        $this->getView()->assign('data', $this->data);
    }

    public function addMenuDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $name = parent::paramsIn($this->getRequest()->getPost('name'), 4);
            $url = parent::paramsIn($this->getRequest()->getPost('url'), 4);
            $level = parent::paramsIn($this->getRequest()->getPost('level'), 1);
            $status = parent::paramsIn($this->getRequest()->getPost('status'), 1);
            $sort = parent::paramsIn($this->getRequest()->getPost('sort'), 1);
            $parent_id = parent::paramsIn($this->getRequest()->getPost('parentId'), 1);
            $menu_icon = parent::paramsIn($this->getRequest()->getPost('menuIcon'), 4);

            if ($level==1) {
                $menu_icon="menu-icon $menu_icon";
            } else {
                $menu_icon='menu-icon fa fa-caret-right';
            }

            $menu = new MenuModel;
            $menu->setParentId($parent_id);
            $menu->setName($name);
            $menu->setLevel($level);
            $menu->setSort($sort);
            $menu->setUrl($url);
            $menu->setStatus($status);
            $menu->setIcon($menu_icon);
            parent::$db_resouce->persist($menu);
            parent::$db_resouce->flush();
            parent::$db_resouce->clear();
            if ($menu->getId()) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }*/

    /*
    //TODO (annotations使用)关联查询,1对1,1对多,多对多
    public function testAction()
    {
        Dispatcher::getInstance()->autoRender(0);

        //直接用只需加个model
        //简单查找
        $au = new AdminUsersModel();
        //$article2 = $this->_entity->find('AdminUsersModel', 1);
        //print_r($article2);
        $repository = $this->_entity->getRepository('AdminUsersModel');
        $res = $repository->findBy(array('user_name' => 'test'));
        foreach ($res as $v) {
            echo ' - ' . $v->getId() . PHP_EOL;
        }
    }
    public function getFnodeAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $menu = new MenuModel();
        echo json_encode($menu->getF());
    }

    // 添加权限
    public function addPermissionAction()
    {
        $this->data['sub_title'] = '添加权限';
        //获得当前用户的级别
        //$uid = Session::getInstance()->get("uid");
        //用户列表
        $u = new UserModel;
        $this->data['user_list'] = $u->userList();
        //模块列表
        $menu = new MenuModel;
        $this->data['l1'] = $menu->getm();
        $this->data['l2'] = $menu->gets();

        $this->data['uname'] = Session::getInstance()->get("username");
        $this->data['mname'] = Session::getInstance()->get("mname");

        $this->data['tabname'][1]='一级模块';
        $this->data['tabname'][2]='二级模块';
        $this->data['button'][1]='添加';
        $this->data['button'][2]='取消';

        $this->data['formaction'] = "/admin_menu/addPermissionDo";
        $this->data['formmethod'] = "post";
        $this->data['formrefer'] = "/admin_menu/list";

        $this->getView()->assign('data', $this->data);
    }

    public function getUmenuAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $menu = new MenuModel();
        $uid = $this->getRequest()->get('id');
        $al['f'] = $menu->getm($uid);
        $al['s'] = $menu->gets($uid);

        echo json_encode($al);
    }

    public function addPermissionDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $menu = new MenuModel();
        if ($this->getRequest()->isPost()) {
            $uid=$this->getRequest()->getPost('uid');
            $u = new UserModel();
            $userinfo = $u->getUserinfo($uid);
            $role_id = $userinfo[0]['role_id'];
            $nodes = $this->getRequest()->getPost('node');
            //按照长度填充数组
            $tmp = array();
            $role = array_pad($tmp, count($nodes), $role_id);
            $data = array_map(null, $role, $nodes);
            if ($menu->addPer($data)) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }*/
}
