<?php
class Admin_UserController extends Yaf_Controller_Abstract
{
	 private $_layout;
     public function init(){
        $sign = isset($_SESSION['info'])?$_SESSION['info']:"";
        if(empty($sign)){
            header("Location:/admin_login");exit;
        }             
         /*use layout*/
         $this->_dispatcher = Yaf_Registry::get("dispatcher");
         $this->_layout = new LayoutPlugin('layout.phtml');
         $this->_dispatcher->registerPlugin($this->_layout);
     }
     /*
      *show admin info
      */
     public function IndexAction()
     {
     	$User = new AdminModel();
     	$User_info = $User->show_admins();

     	$this->_view->users_info = $User->show_admins();
     	$this->_layout->meta_title = '管理员列表';

     }
     /*
      *add admin page
      *
      */
     public function AdduserAction()
     {
     	$this->_layout->meta_title = '添加管理员';
     }

     /*
      * insert admin data
      *
      */
     public function InsertAction()
     {
     	$data = array(
     		'username' => ($_POST['username'])?htmlspecialchars($_POST['username']):"",
     		'password' => ($_POST['password'])?htmlspecialchars($_POST['password']):"",
     		'realname'    =>  ($_POST['realname'])?htmlspecialchars($_POST['realname']):"",
     		'email'    =>  ($_POST['email'])?htmlspecialchars($_POST['email']):"",
     		'nickname' => ($_POST['nickname'])?htmlspecialchars($_POST['nickname']):""
     		);
     	$UserInsert = new AdminModel();
     	$id = $UserInsert->insert_admin($data);
     	if(!empty($id)){
     		header("Location:index");
     	}else{
     		header("Location:adduser");
     	}
     }

     /*
      * delete one admin info
      * 
      */
     public function DelAction()
     {
     	$id = ($_POST['id'])?intval($_POST['id']):"";

     	if(empty($id)){
     		echo "101:删除失败！请正确选择管理员！";exit;
     	}else{
     		$UserDel = new AdminModel();
     		$row = $UserDel->del_admin($id);
     		if(empty($row)){
     			echo "101:删除失败，请重试！";exit;
     		}else{
     			echo "100:删除成功！";exit;
     		}
     	}
     }

     /*
      *show edit admin info 
      *
      */
     public function EditAction()
     {
     	$UserInfo = new AdminModel();
     	$id = $this->getRequest()->getParam("id", 0);
     	if(!empty($id)){
        	$this->_view->userinfo = $UserInfo->get_info($id);
     	}else{
     		header("Location:index");
     	}
     	$this->_layout->meta_title = '管理员信息';
     }

     /*
      *edit admin info 
      *
      */
     public function UpdateAction()
     {
     	$id = ($_POST['id'])?htmlspecialchars($_POST['id']):"";
     	$data = array(
     		'username' => ($_POST['username'])?htmlspecialchars($_POST['username']):"",
     		'realname'    =>  ($_POST['realname'])?htmlspecialchars($_POST['realname']):"",
     		'email'    =>  ($_POST['email'])?htmlspecialchars($_POST['email']):"",
     		'nickname' => ($_POST['nickname'])?htmlspecialchars($_POST['nickname']):""
     		);
	     $password = ($_POST['password'])?htmlspecialchars($_POST['password']):"";
         if(!empty($password)){
         	$data['password'] = md5($password);
         }
     	$UserUpdate = new AdminModel();
     	$lastid = $UserUpdate->update_admin($data,$id);
     	if(!empty($lastid)){
     		header("Location:index");
     	}else{
     		header("Location:index");
     	}
    }   
}
