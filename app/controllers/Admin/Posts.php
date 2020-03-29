<?php
use Yaf\Dispatcher;
use Yaf\Registry;
use Yaf\Session;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Admin_PostsController extends Admin_BaseController
{
    // private $posts;
    public $tmp = array();

    public function init()
    {
        parent::init();
    }

    public function ListAction()
    {
        $this->data['module_name'] = '文章管理';
        $this->data['sub_title'] = '文章列表';
        $this->data['tab_sub_title'] = '文章列表';
        $this->data['data_title'] = array('Id','标题','分类','状态','修改日期','操作');
        $this->data['sAjaxSource'] = '/admin/posts/listData';
        $this->data['changeurl'] = '/admin/posts/change/id/';
        $this->data['rows'] = $this->rows;
        $this->getView()->assign('data', $this->data);
    }

    public function listDataAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $sql = "SELECT p FROM PostsModel p ";
        $sql_cnt = "SELECT count(distinct p.Id) cnt  FROM PostsModel p ";
        if ($this->getRequest()->isGet()) {
            $search = parent::paramsIn($this->getRequest()->get('sSearch'), 4);
            if (isset($search) && trim($search) != '') {
                $sql .= " where p.postCate like '%$search%' or p.postTitle like '%$search%' 
                or p.postContent like '%$search%'";
                $sql_cnt .= " where p.postCate like '%$search%' or p.postTitle like '%$search%' 
                or p.postContent like '%$search%'";
            }
        }
        $count_res = parent::$db_resouce->createQuery($sql_cnt)->getResult();
        if ($count_res && isset($count_res[0])) {
            $total_rows = $count_res[0]['cnt'];
        } else {
            $total_rows = 0;
        }
        // getSingleResult
        // getFirstResult
        // getMaxResults
        $limit_start = parent::paramsIn($this->getRequest()->get('iDisplayStart'), 1);
        $limit_rows = parent::paramsIn($this->getRequest()->get('iDisplayLength'), 1);
        $sql .= " ORDER BY p.Id DESC";
        $query = parent::$db_resouce->createQuery($sql)
        ->setFirstResult($limit_start)->setMaxResults($limit_rows);
        $res = new Paginator($query);
        // $this->dump($value);
        $data = [];
        if ($res && !empty($res)) {
            foreach ($res as $k => $value) {
                $data[$k]['id']=$value->getId();
                $data[$k]['title']=$value->getPostTitle();
                $cate_id = $value->getPostCate();
                if ($cate_id) {
                    $where_cate['Id'] = $cate_id;
                    $where_cate['status'] = 1;
                    $cate = parent::$db_resouce->getRepository("PostCategoryModel")->findBy($where_cate);
                    if ($cate) {
                        $cate_name = $cate[0]->getPostCateName();
                    } else {
                        $cate_name = '无分类';
                    }
                } else {
                    $cate_name = '分类被删除';
                }
                $data[$k]['cate']=$cate_name;
                switch ($value->getPostStatus()) {
                    case 1:
                        $status = '待审核';
                        break;
                    case 2:
                        $status = '草稿';
                        break;
                    case 3:
                        $status = '审核通过';
                        break;
                    case 0:
                        $status = '删除';
                        break;
                }
                $data[$k]['status']=$status;
                $data[$k]['change_time']=$value->getPostModified();
                $data[$k]['show']=$value->getPostUrl();
            }
            $tmp['iTotalRecords'] = $total_rows; //返回行数
            $tmp['iTotalDisplayRecords'] = $total_rows; //过滤后的行数
            $tmp['data'] = $data;
            echo json_encode($tmp);
            die;
        } else {
            echo parent::responseResult(0, 'fail');
            die;
        }
    }

    public function changeAction()
    {
        $data['module_name'] = '文章管理';
        $data['sub_title'] = '修文章';
        //防止重复提交
        // $this->data['xsr'] = Tools::initXsrf();
        if ($this->getRequest()->isGet()) {
            $pid = $this->getRequest()->get('id');
            if (isset($pid) && $pid) {
                $where=['Id'=>$pid];
                $post_res = parent::$db_resouce->getRepository("PostsModel")->findBy($where);
                $article = [];
                if ($post_res) {
                    foreach ($post_res as $val) {
                        //TODO 如果是html的内容，写入和读出都需要转译
                        $article['title'] = $val->getPostTitle();
                        // $article['seo'] = $val->getPostKeyWord();
                        $article['category'] = $val->getPostCate();
                        $content = parent::paramsOut($val->getPostContent(),5);
                        $article['content'] = $this->clearHtml($content); 
                        if (strlen($val->getPostTag()) > 0) {
                            $article['tag'] = explode(',', $val->getPostTag());
                        } else {
                            $article['tag'] = '';
                        }
                        $article['status'] = $val->getPostStatus();
                    }
                }
                $status_list = [
                    0=>'删除',
                    1=>'待审核',
                    2=>'草稿',
                    3=>'审核通过',
                ];
                $where_cate=['status'=>1];
                $cate_res = parent::$db_resouce->getRepository("PostCategoryModel")->findBy($where_cate);
                $cate_list = [];
                if ($cate_res) {
                    foreach ($cate_res as $k => $val) {
                        $cate_list[$k]['id'] = $val->getPostCateId();
                        $cate_list[$k]['name'] = $val->getPostCateName();
                    }
                    unset($cate_res);
                }
                $where_tag=['status'=>1];
                $tag_list = [];
                $tag_res = parent::$db_resouce->getRepository("PostTagModel")->findBy($where_tag);
                if ($tag_res) {
                    foreach ($tag_res as $k => $val) {
                        $tag_list[$k]['id'] = $val->getTagId();
                        $tag_list[$k]['name'] = $val->getTagName();
                    }
                    unset($tag_res);
                }

                //TODO 预览地址(文章状态不为0的情况下才有)
                //文章详情页
                $data['view'] = SITE_URL.'/p/'.$pid;
                $data['umeditor_home'] = EDITOR_HOME;
                $data['to_action'] = '/admin/posts/changeDo';
                $data['to_refer'] = '/admin/posts/list';

                $data['post_id'] = $pid;
                $data['article'] = $article;
                $data['cate_list'] = $cate_list;
                $data['tag_list'] = $tag_list;
                $data['status_list'] = $status_list;
                $this->getView()->assign('data', $data);
            }
        } else {
            $this->redirect('/admin/posts/list');
        }
    }

    public function changeDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            // Tools::doSubmit($this->getRequest()->getPost('xsr'));
            $posts_arr = $this->getRequest()->getPost();
            if (!empty($posts_arr)) {
                if ($posts_arr['post_id']) {
                    $pid = parent::paramsIn($posts_arr['post_id'], 1);
                } else {
                    echo $this->responseResult(2, 'no pid');
                    die;
                }
                $p = parent::$db_resouce->find('PostsModel', $pid);
                if ($p === null) {
                    echo $this->responseResult(2, 'pid not found');
                    die;
                }

                if ($posts_arr['post_title']) {
                    $title = parent::paramsIn($posts_arr['post_title'], 4);
                    $p->setPostTitle($title);
                }

                if ($posts_arr['post_cate']) {
                    $cate = parent::paramsIn($posts_arr['post_cate'], 1);
                    $p->setPostCate($cate);
                }
                if ($posts_arr['post_tag']) {
                    $tag = parent::paramsIn(join(",", $posts_arr['post_tag']), 4);
                    $p->setPostTag($tag);
                }
                if ($posts_arr['post_status']) {
                    $status = parent::paramsIn($posts_arr['post_status'], 1);
                    $p->setPostStatus($status);
                }
                $post_content = trim($posts_arr['post_content']);
                if ($post_content) {
                    $content = parent::paramsIn($post_content, 5);
                    $p->setPostContent($content);
                }
                if (!empty($posts_arr['post_img'])) {
                    $img_str = trim($posts_arr['post_img'], ',');
                    $p->setPostImage($img_str);
                }
                $now = date('Y-m-d h:i:s');
                $p->setPostModified($now);
                parent::$db_resouce->flush();
                echo $this->responseResult(1, 'success');
                /*if (!empty($pid)) {
                    $sql = "UPDATE PostsModel p set {$set} WHERE p.Id={$pid}";
                    $query = parent::$db_resouce_w->createQuery($sql);
                    $res = $query->execute();
                }*/
            } else {
                echo $this->responseResult(2, 'post not data');
                die;
            }
        } else {
            echo $this->responseResult(2, 'not post');
            die;
        }
    }

    public function addAction()
    {
        //防止重复提交
        // $this->data['xsr'] = Tools::initXsrf();
        $data['module_name'] = '文章管理';
        $data['sub_title'] = '创建文章';
        $status_list = [
        0=>'删除',
        1=>'待审核',
        2=>'草稿',
        3=>'审核通过',
        ];
        $where_cate=['status'=>1];
        $cate_res = parent::$db_resouce->getRepository("PostCategoryModel")->findBy($where_cate);
        $cate_list = [];
        if ($cate_res) {
            foreach ($cate_res as $k => $val) {
                $cate_list[$k]['id'] = $val->getPostCateId();
                $cate_list[$k]['name'] = $val->getPostCateName();
            }
            unset($cate_res);
        }
        $where_tag=['status'=>1];
        $tag_list = [];
        $tag_res = parent::$db_resouce->getRepository("PostTagModel")->findBy($where_tag);
        if ($tag_res) {
            foreach ($tag_res as $k => $val) {
                $tag_list[$k]['id'] = $val->getTagId();
                $tag_list[$k]['name'] = $val->getTagName();
            }
            unset($tag_res);
        }

        //展示顺序
        $data['show_num']=$this->getLastId();
        $data['ueditor_home'] = '/ueditor/index';
        $data['to_action'] = '/admin/posts/addDo';
        $data['to_refer'] = '/admin/posts/list';

        $data['cate_list'] = $cate_list;
        $data['tag_list'] = $tag_list;
        $data['status_list'] = $status_list;
        //最大的文件id
        $this->getView()->assign('data', $data);
    }
    
    public function addDoAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $posts_arr = $this->getRequest()->getPost();
            if (!empty($posts_arr)) {
                if (!empty($posts_arr['post_title'])) {
                    $title = parent::paramsIn($posts_arr['post_title'], 4);
                } else {
                    echo $this->responseResult(2, 'no title');
                    die;
                }

                if (!empty($posts_arr['post_cate'])) {
                    $cate = parent::paramsIn($posts_arr['post_cate'], 1);
                } else {
                    echo $this->responseResult(2, 'no cate');
                    die;
                }

                if (!empty($posts_arr['post_tag'])) {
                    $tag = parent::paramsIn(join(",", $posts_arr['post_tag']), 4);
                }

                if (!empty($posts_arr['post_status'])) {
                    $status = parent::paramsIn($posts_arr['post_status'], 1);
                } else {
                    echo $this->responseResult(2, 'no status');
                    die;
                }

                $post_content = trim($posts_arr['post_content']);
                if (!empty($post_content)) {
                    $content = parent::paramsIn($post_content, 5);
                }
                if (!empty($posts_arr['post_img'])) {
                    $img_str = trim($posts_arr['post_img'], ',');
                } else {
                    $img_str = '';
                }

                if (!empty($posts_arr['post_order'])) {
                    $post_order = parent::paramsIn("'{$posts_arr['post_order']}'", 1);
                } else {
                    $post_order = $this->getLastId();
                }

                if (!empty($posts_arr['post_keyword'])) {
                    $post_keyword = parent::paramsIn("'{$posts_arr['post_keyword']}'", 4);
                } else {
                    $post_keyword = $title;
                }
                if (!empty($title) && !empty($content) && !empty($tag) && !empty($cate)) {
                    $now = date('Y-m-d H:i:s');
                    $post = new PostsModel;
                    $post->setPostTitle($title);
                    //发帖人
                    $post->setUserId($this->current_uid);
                    $post->setPostCate($cate);
                    $post->setPostTag($tag);
                    $post->setPostStatus($status);
                    $post->setPostContent($content);
                    $post->setCommentStatus(1);
                    //TODO 暂时相当于标题
                    // $post->setPostKeyword($post_keyword);
                    //文章中的图片地址
                    $post->setPostImage($img_str);
                    //文章前台特殊位置展示顺序排序
                    $post->setPostOrder($post_order);
                    $post->setCommentCount(0);
                    $post->setPostDate($now);
                    $post->setPostModified($now);
                    $post->setPostViewCount(0);
                    $post->setPostUrl('/p/'.$this->getLastId());
                    parent::$db_resouce->persist($post);
                    parent::$db_resouce->flush();
                    parent::$db_resouce->clear();
                    //doctirne 调试
                    if ($post->getId()) {
                        echo $this->responseResult(1, 'success');
                        die;
                    } else {
                        echo $this->responseResult(2, 'insert fail');
                        die;
                    }
                }
            } else {
                echo $this->responseResult(2, 'no data');
                die;
            }
        } else {
            echo $this->responseResult(2, 'no post');
            die;
        }
    }

    public function catchAction()
    {
    }

    private function getLastId(array $where = [])
    {
        if (!empty($where)) {
            $where_post['postStatus'] = $where;
        } else {
            $where_post['postStatus'] = [1,2,3,4];
        }

            $last_id = parent::$db_resouce->getRepository("PostsModel")->findBy($where_post, ['Id'=>'desc'], 1, 0);
        if ($last_id) {
            return $last_id[0]->getId()+1;
        }
            return 0;
    }

    public function doImgAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        if ($this->getRequest()->isPost()) {
            $files = Registry::get('config');
            $f = new Upload($files['files']['config'], 'image');
            $f_arr = $_FILES['imgFile'];
            $res = $f->upload($f_arr);
            if ($res['error'] === 0) {
                $res['url'] = '/' . $res['url'];
            }
            echo json_encode($res);
        }
        //TODO 水印
    }

    public function manImgAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $dir = $this->getRequest()->get('dir');
        $path = strtolower($this->getRequest()->get('path'));
        $files = Registry::get('config');
        $f = new Upload($files['files']['config'], $dir);
        echo json_encode($f->showFile($path));
    }

    public function delImgAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $files = Registry::get('config');
        $dir = 'image';
        $url = $this->getRequest()->getPost('url');
        $type = strtolower($this->getRequest()->getPost('isd'));
        $f = new Upload($files['files']['config'], $dir);
        if ($f->delFile($url, $type)) {
            echo 'succeed';
        } else {
            echo true;
        }
    }
}
