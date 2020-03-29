<?php
use Yaf\Controller_Abstract;
use Yaf\Registry;
use Yaf\Session;
use Yaf\Dispatcher;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Home_PostController extends Home_BaseController
{
    public function init()
    {
        parent::init();
        $this->setViewPath(APP_PATH . '/views');
    }

    public function detailAction()
    {
        $pid = parent::paramsIn($this->getRequest()->get('p'), 1);
        if (empty($pid)) {
            $this->redirect('/');
        }
        if (empty($pid)) {
            echo parent::responseResult(2, 'id is not effect or id is null');
        }
        $where['Id'] = $pid;
        $where['postStatus'] = 3;
        $res = parent::$db_resouce->getRepository('PostsModel')->findBy($where);
        $data = [];
        if ($res && !empty($res[0])) {
            $data['title'] = $res[0]->getPostTitle();
            // $data['content'] = $this->clearHtml(parent::paramsOut($res[0]->getPostContent(),4),['&nbsp;']);
            $data['content'] = parent::paramsOut($res[0]->getPostContent(),5);
            $data['publish'] = $res[0]->getPostModified();
        }
        // $data['sAjaxSource'] = '/p/detailData/'.$pid;
        $this->getView()->assign('data', $data);
    }

    public function recentAction(){
        Dispatcher::getInstance()->autoRender(0);
        $where['postStatus'] = 3;
        $res = parent::$db_resouce->getRepository('PostsModel')->findBy($where,['Id'=>'desc'],5,0);
        $data = [];
        if ($res && !empty($res[0])) {
            foreach($res as $k=>$val){
            $data[$k]['title'] = $val->getPostTitle();
            $data[$k]['publish'] = $val->getPostModified();
            $data[$k]['url'] = $val->getPostUrl();
            }
            echo parent::responseResult(1, 'success', $data);
            exit(0);
        }
        echo parent::responseResult(0, 'fail', $data);
        exit(1);
    }

    public function popularAction(){
        Dispatcher::getInstance()->autoRender(0);
        $where['postStatus'] = 3;
        $res = parent::$db_resouce->getRepository('PostsModel')->findBy($where,['postViewCount'=>'desc'],5,0);
        $data = [];
        if ($res && !empty($res[0])) {
            foreach($res as $k=>$val){
            $data[$k]['title'] = $val->getPostTitle();
            $data[$k]['publish'] = $val->getPostModified();
            $data[$k]['url'] = $val->getPostUrl();
            }
            echo parent::responseResult(1, 'success', $data);
            exit(0);
        }
        echo parent::responseResult(0, 'fail', $data);
        exit(1);
    }

    public function searchAction(){
        $this->data['contents'] = [];
        $search_key = $this->paramsIn($this->getRequest()->get('s'),4);
        if(empty($search_key)){
            //TODO 跳回去  
        }
        $this->data['s'] = $search_key;
        //总数
        $page = parent::paramsIn($this->getRequest()->get('page'), 1);
        $size = 10;
        $offset =  ($page > 1) ? ($page -1)*$size : 0;
        $sql = "SELECT count(p.Id) cnt FROM PostsModel p WHERE p.postStatus=:status and (p.postTitle LIKE :search OR p.postContent LIKE :search) ORDER BY p.Id";
        $total_query = parent::$db_resouce->createQuery($sql)->setParameters(['status'=>3,'search'=>'%'.$search_key.'%']);
        $total_rows = $total_query->getSingleScalarResult();
        $sql_post = "SELECT 
            p
            FROM PostsModel p 
            WHERE 
            p.postStatus=:status
            and (p.postTitle LIKE :search OR p.postContent LIKE :search)
            ORDER BY p.postModified DESC";
        //->setParameter(1, array("name" => "Audi A8", "year" => 2010))
        $query = parent::$db_resouce->createQuery($sql_post)
                       ->setParameters(['status'=>3,'search'=>'%'.$search_key.'%'])
                       ->setFirstResult($offset)
                       ->setMaxResults($size);
        $res = new Paginator($query);
        if ($res && !empty($res)) {
            $data = [];
            foreach ($res as $k=>$value) {
               $data[$k]['id'] = $value->getId();
               $data[$k]['title'] = $value->getPostTitle();
               $content = $this->paramsOut($value->getPostContent(),5);
               $content_str = $this->clearHtml($content);
               $data[$k]['description'] = substr($content_str,0,450);
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
            $this->data['contents'] = $data;
            if($total_rows <= $size){
                $total_pages = 1;
            }else{
                $total_pages = ceil($total_rows/$size);
            }
            $this->data['current_page'] = $page;
            $this->data['max_pages'] = $total_pages;
            if($this->data['current_page']==1){
                $this->data['pages_start'] = 1;
                if($this->data['max_pages'] > 9){
                    $this->data['pages_stop'] = 9;
                }else{
                    $this->data['pages_stop'] = $this->data['max_pages'];
                }
            }elseif($this->data['current_page']>1 && $this->data['current_page']==$this->data['max_pages']){
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
        }
        $this->getView()->assign('data', $this->data);
    }

    public function detailDataAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $pid = parent::paramsIn($this->getRequest()->get('p'), 1);
        if (empty($pid)) {
            echo parent::responseResult(2, 'id is not effect or id is null');
        }

        $where['Id'] = $pid;
        $where['postStatus'] = 4;
        $res = parent::$db_resouce->getRepository('PostsModel')->findBy($where);
        $data = [];
        if ($res && !empty($res[0])) {
            $data['title'] = $res[0]->getPostTitle();
            $data['content'] = str_replace("\r\n", "", parent::paramsOut($res[0]->getPostContent(), 4));
            $data['post_date'] = $res[0]->getPostModified();
            $data['keyword'] = $res[0]->getPostKeyWord();
            echo parent::responseResult(1, 'success', $data);
        } else {
            echo parent::responseResult(2, 'fail');
        }
    }

    public function ContentAction()
    {
        Dispatcher::getInstance()->disableView(0);
        //当前条数
        $page = parent::paramsIn($this->getRequest()->get('page'), 1);
        $size = parent::paramsIn($this->getRequest()->get('size'), 1);
        if ($page===false ||$size===false) {
            echo parent::responseResult(0, '参数错误');
        }

        $offset =  ($page > 1) ? ($page -1)*$size : 0;
        $sql = "SELECT p FROM PostsModel p WHERE p.postStatus=1 ORDER BY p.Id desc";
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
                $list[$key]['url'] = '/p/'.$value->getPostId();
                $img_str = $value->getPostImage();
                if (!empty($img_str)) {
                    $list[$key]['image'] = $img_str;
                } else {
                    $list[$key]['image'] = '';
                }
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
