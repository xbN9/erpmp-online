<?php
use Yaf\Session;
use Yaf\Application;
use Yaf\Registry;

class Home_BaseController extends CommonController
{
    protected $menuArr = [];

    public function init()
    {
        parent::init();
        echo 11;
        die;

        if (!empty($this->home_username)) {
            $this->data['home_username'] = $this->home_username;
            //TODO 用户表获得(登录后可以从userifno中获得)
            $this->data['user_img'] = '/avatars/avatar.png';
            $this->data['home_userInfo'] = '/u/'.$this->home_uid;
        } else {
            $this->data['home_username'] = 'Tom';
            $this->data['user_img'] = '/avatars/avatar.png';
            $this->data['home_userInfo'] = '#';
        }
        $this->data['recommend_url'] = '/index/indexRecommend';
        $this->data['hot_recommend_url'] = '/index/hotRecommend';
        $this->data['billboards_url'] = '/index/indexRecommend';
        $this->data['c_url'] = $this->current_url;

        //如果是搜索页面显示search-key
        $this->data['search_key'] = '';
        if (strpos($this->data['c_url'], 'search')!=false) {
            $this->data['search_key']= $this->getRequest()->get('s');
        }
        $this->data['c_c_id'] = $this->getRequest()->get('c');
        //$em->getConnection()->isConnectedToMaster()
        //Doctrine_Manager::getInstance()->getConnection(‘master’)
        //手动连接使用：parent::$db_resouce->connect('master');
        $menu = parent::$db_resouce->getRepository("PostCategoryModel")
        ->findBy(array('status' => 1), array('sort' => 'ASC'));
        if (!empty($menu)) {
            foreach ($menu as $value) {
                $this->data['menu'][$value->getPostCateSort()]['id'] = $value->getPostCateId();
                $this->data['menu'][$value->getPostCateSort()]['name'] = $value->getPostCateName();
                if ($value->getPostCateId()==6) {
                    $this->data['menu'][$value->getPostCateSort()]['url'] = SITE_URL;
                } else {
                    $this->data['menu'][$value->getPostCateSort()]['url'] = '/c/'.$value->getPostCateId().'/page/1';
                }
                $this->data['menu'][$value->getPostCateSort()]['icon'] = $value->getPostCateIcon();
                $this->menuArr[$value->getPostCateSort()]['id'] = $value->getPostCateId();
                $this->menuArr[$value->getPostCateSort()]['sort'] = $value->getPostCateSort();
            }
        }
        $this->getView()->render('/index/layout/layout.html', $this->data);
    }
}
