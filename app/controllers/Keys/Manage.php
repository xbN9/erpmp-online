<?php

class Keys_ManageController extends Admin_BaseController {

    public function init() {
        parent::init();
        $this->setViewPath(APP_PATH . '/views');
    }

    public function IndexAction(){
        
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        
        $this->data['sub_cate'] = '指标管理';
        $this->data['sub_title'] = '列表';
        
        
        $setting = new KeySettingModel();
        $this->data['datas'] = $setting->listAll();
        
        $this->getView()->display('/keys/index.html', $this->data);
    }

    public function editAction(){
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        
        $this->data['sub_cate'] = '指标管理';
        $this->data['sub_title'] = '增加指标';
        
        $setting = new KeySettingModel();
        $param = array();

        if($this->getRequest()->getPost('sql_text')){
            $keyCnName       = $this->getRequest()->getPost('key_cn_name');
            $demensionCnName = $this->getRequest()->getPost('demension_cn_name');
            $sqlText         = $this->getRequest()->getPost('sql_text');
        
            if(!empty($keyCnName) && !empty($demensionCnName) && !empty($sqlText)){
                $keyEnName = strtolower(Word2PY::encode($keyCnName));
                $demensionEnName = strtolower(Word2PY::encode($demensionCnName));

                $param['key_cn_name'] = $keyCnName;
                $param['key_en_name'] = $keyEnName;
                $param['demension_cn_name'] = $demensionCnName;
                $param['demension_en_name'] = $demensionEnName;
                $param['sql_text'] = $sqlText;
                $param['create_time'] = date('Y-m-d H:m:s',time());
                if($this->getRequest()->get('keyid')){
                    $insertId = $setting->modify($this->getRequest()->get('keyid'),$param['sql_text']);    
                }else{
                    $insertId = $setting->addKey($param);
                }
                
            }
        }else if(!empty($this->getRequest()->get('keyid'))){
            $result = $setting->getOne($this->getRequest()->get('keyid'));
            $param = $result[0];
        }

        $this->data['data'] = $param;
        
        $this->getView()->display('/keys/detail.html', $this->data);
    }
}
