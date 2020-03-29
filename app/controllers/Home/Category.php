<?php
use Yaf\Controller_Abstract;
use Yaf\Registry;
use Yaf\Session;
use Yaf\Dispatcher;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Home_CategoryController extends Home_BaseController
{
    private $page_size = 10;
    public function init()
    {
        parent::init();
    }

    public function listAction()
    {
        $cid = parent::paramsIn($this->getRequest()->get('c'), 1);
        if (empty($cid)) {
            $this->redirect('/');
        }
        $this->data['id'] = $cid;
        $this->data['contents'] = [];
        //当前条数
        //总数
        $page = parent::paramsIn($this->getRequest()->get('page'), 1);
        $size = 10;
        $offset =  ($page > 1) ? ($page -1)*$size : 0;
        $sql = "SELECT count(p.Id) cnt FROM PostsModel p WHERE p.postCate=?1 and p.postStatus=?2 ORDER BY p.Id";
        $total_query = parent::$db_resouce->createQuery($sql)
										  ->setParameters(array(1=>$cid,2=>3));
        $total_rows = $total_query->getSingleScalarResult();
        $sql_post = "SELECT 
            p
            FROM PostsModel p 
            WHERE 
            p.postCate=?1
            AND p.postStatus=?2
            ORDER BY p.postModified DESC";
        //->setParameter(1, array("name" => "Audi A8", "year" => 2010))
        $query = parent::$db_resouce->createQuery($sql_post)
                       ->setParameters(array(1=>$cid,2=>3))
                       ->setFirstResult($offset)
                       ->setMaxResults($size);
        $res = new Paginator($query);
        if ($res && !empty($res)) {
            $data = [];
            foreach ($res as $k=>$value) {
               $data[$k]['id'] = $value->getId();
               $data[$k]['title'] = $value->getPostTitle();
               $data[$k]['description'] = substr($this->clearHtml($value->getPostContent(),['&nbsp;']),0,450);
               $data[$k]['publish'] = strstr($value->getPostModified(),' ',true);
               $data[$k]['cate_id'] = $value->getPostCate();
               $data[$k]['tag_id'] = $value->getPostTag();
               $data[$k]['url'] = $value->getPostUrl();
               if (!empty($value->getPostImage())) {
                   $data[$k]['image'] = $value->getPostImage();
               } else {
                   $data[$k]['image'] = '';
               }
            }
            //无数据
            if(!$data){
                $this->redirect(SITE_URL);
            }
            $this->data['contents'] = $data;
            $total_pages = ceil($total_rows/$size);
            $this->data['current_page'] = $page;
            $this->data['max_pages'] = $total_pages;
            if($this->data['current_page']==1){
                $this->data['pages_start'] = 1;
                $this->data['pages_stop'] = 9;
            }elseif($this->data['current_page']==$this->data['max_pages']){
                $this->data['pages_start'] = $this->data['max_pages'] - 9;
                $this->data['pages_stop'] = $this->data['max_pages'];
            }else{
                $start_page = $this->data['current_page'] - 4;
                if($start_page >0){
                    $pages_start = $start_page;
                }else{
                    $pages_start = 1;
                } 
                $this->data['pages_start'] = $pages_start;
                $pages_stop = $this->data['current_page'] + 5;
                $this->data['pages_stop'] = $pages_stop;
            }
            $this->getView()->assign('data', $this->data);
        }
    }

    public function listDataAction()
    {
        Dispatcher::getInstance()->disableView(0);
        //当前条数
        $page = parent::paramsIn($this->getRequest()->get('page'), 1);
        $size = parent::paramsIn($this->getRequest()->get('size'), 1);
        $cid = parent::paramsIn($this->getRequest()->get('c'), 1);
        if (empty($cid) || $page===false || $size===false) {
            echo parent::responseResult(0, '参数错误');
        }

        $offset =  ($page > 1) ? ($page -1)*$size : 0;
        $sql = "SELECT p FROM PostsModel p WHERE p.postCate=$cid AND p.postStatus=1 ORDER BY p.Id";
        $query = parent::$db_resouce->createQuery($sql)
                       ->setFirstResult($offset)
                       ->setMaxResults($size);

        $res = new Paginator($query);
        $total_size = count($res);
        if ($res) {
            $list = [];
            foreach ($res as $key => $value) {
                $list[$key]['id'] = $value->getPostId();
                $list[$key]['title'] = parent::paramsOut($value->getPostTitle(), 4);
                $list[$key]['introduce'] = mb_substr($value->getPostContent(), 0, 50);
                $list[$key]['time'] = $value->getPostModified();
                $list[$key]['comment_count'] = $value->getCommentCount();
                $list[$key]['views'] = $value->getCommentCount();
                $list[$key]['url'] = '/post/detail/'.$value->getPostId();
                // $list[$key]['image'] = $value->getPostImage();
                $list[$key]['image'] = "";
            }
            $data['current_page'] = $page;
            $data['total_page'] = ceil($total_size/$size);
            $data['size'] = $size;
            $data['list'] = $list;
            echo parent::responseResult(1, 'success', $data);
        } else {
            echo parent::responseResult(0, '无数据');
        }
    }
}
