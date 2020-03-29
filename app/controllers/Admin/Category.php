<?php
use Yaf\Dispatcher;
use Yaf\Registry;
use Yaf\Session;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Admin_CategoryController extends Admin_BaseController
{
    
    public function init()
    {
        parent::init();
    }

    public function listAction()
    {
        $data['module_name'] = '前台菜单管理';
        $data['sub_title'] = '菜单列表';
        $data['tab_sub_title'] = '菜单列表';
        $data['data_title'] = array('Id','名称', '级别', '状态','排序','查看','操作');
        $data['sAjaxSource'] = '/admin/category/listData';
        $data['changeurl'] = '/admin/category/change/id/';
        $data['look'] = '/c/';
        $data['rows'] = $this->rows;
        $this->getView()->assign('data', $data);
    }

    public function listDataAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        //搜索
        $search = parent::paramsIn($this->getRequest()->get('sSearch'), 4);

        $limit_start = parent::paramsIn($this->getRequest()->get('iDisplayStart'), 1);
        $limit_rows = parent::paramsIn($this->getRequest()->get('iDisplayLength'), 1);

        $sql = "SELECT p FROM PostCateGoryModel p ";
        $sql_cnt = "SELECT count(distinct p.Id) cnt FROM PostCateGoryModel p ";
        if (isset($search) && trim($search) != '') {
            $sql .= "WHERE p.name like '%$search%' or p.level like '%$search%'";
            $sql_cnt .= "WHERE p.name like '%$search%' or p.level like '%$search%'";
        }

        $count = parent::$db_resouce->createQuery($sql_cnt)->getResult();
        if ($count) {
            $count = $count[0]['cnt'];
        } else {
            $count = 0;
        }

        $limit_start = 0;
        $limit_rows = 10;
        $query = parent::$db_resouce->createQuery($sql)
        ->setFirstResult($limit_start)->setMaxResults($limit_rows);
        // $this->sqlDump($query);
        $res = new Paginator($query);
        $data = [];
        if ($res && !empty($res)) {
            foreach ($res as $k => $value) {
                $data[$k]['id']=$value->getPostCateId();
                $data[$k]['name']=$value->getPostCateName();
                $data[$k]['level']=$value->getPostCateLevel();
                $data[$k]['status']= !empty($value->getPostCateStatus()) ? '开启' : '关闭';
                $data[$k]['sort']=$value->getPostCateSort();
            }

            $total_rows = count($count);
            $tmp['iTotalRecords'] = $total_rows; //返回行数
            $tmp['iTotalDisplayRecords'] = $total_rows; //过滤后的行数
            $tmp['data'] = $data;
            echo json_encode($tmp);
        } else {
            echo parent::responseResult(0, 'fail');
        }
    }
    
    public function addAction()
    {
        $data['module_name'] = '前台菜单管理';
        $data['sub_title'] = '创建菜单';
        $data['formaction'] = '/admin/category/addDo';
        $data['formmethod'] = 'post';
        $data['formrefer'] = '/admin/category/list';
        $this->getView()->assign('data', $data);
    }

    public function addDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $name = parent::paramsIn($this->getRequest()->getPost('name'), 4);
            $level = parent::paramsIn($this->getRequest()->getPost('level'), 1);
            $status = parent::paramsIn($this->getRequest()->getPost('status'), 1);
            $sort = parent::paramsIn($this->getRequest()->getPost('sort'), 1);

            $cate = new PostCategoryModel;
            $cate->setPostCateName($name);
            $cate->setPostCateLevel($level);
            $cate->setPostCateSort($sort);
            $cate->setPostCateStatus($status);
            parent::$db_resouce->persist($cate);
            parent::$db_resouce->flush();
            parent::$db_resouce->clear();
            if ($cate->getId()) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }

    public function changeAction()
    {
        $data['module_name'] = '前台菜单管理';
        $data['sub_title'] = '修改前台菜单';
        $cate_id = parent::paramsIn($this->getRequest()->get('id'), 1);
     
        if (empty($cate_id)) {
            $this->redirect('/admin/category/list');
        }

        $where['Id'] = $cate_id;
        $res = parent::$db_resouce->getRepository('PostCategoryModel')->findBy($where);
        if (!$res) {
            $this->redirect('/admin/category/list');
        }

        $data['formArr']['Id'] = $res[0]->getPostCateId();
        $data['formArr']['name'] = $res[0]->getPostCateName();
        $data['formArr']['level'] = $res[0]->getPostCateLevel();
        $data['formArr']['status'] = $res[0]->getPostCateStatus();
        $data['formArr']['sort']  = $res[0]->getPostCateSort();

        $data['formaction'] = '/admin/category/changeDo';
        $data['formmethod'] = 'post';
        $data['formrefer'] = '/admin/category/list';
        $this->getView()->assign('data', $data);
    }

    public function changeDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $menu_id = parent::paramsIn($this->getRequest()->getPost('Id'), 1);
            $set = '';
            $name = parent::paramsIn($this->getRequest()->getPost('name'), 4);
            $set .= "p.name='$name'";
            $level = parent::paramsIn($this->getRequest()->getPost('level'), 1);
            $set .=",p.level=$level";
            $status = parent::paramsIn($this->getRequest()->getPost('status'), 1);
            $set .=",p.status=$status";
            $sort = parent::paramsIn($this->getRequest()->getPost('sort'), 1);
            $set .=",p.sort=$sort";

            if (!empty($menu_id) && $set!='') {
                $sql = "UPDATE  PostCategoryModel p set $set where p.Id={$menu_id}";
                $query = parent::$db_resouce->createQuery($sql);
                $res = $query->execute();
                if ($res) {
                    echo 1;
                } else {
                    echo 2;
                }
            }
        } else {
            echo 2;
        }
    }
}
