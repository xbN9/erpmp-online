<?php

class Drill_ManageController extends Admin_BaseController {

    public function init() {
        parent::init();
        $this->setViewPath(APP_PATH . '/views');
    }

    public function IndexAction(){
        
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        
        $this->data['sub_cate'] = '指标数据';
        
        $keyId = $this->getRequest()->getPost('demension');
        
        if(empty($keyId))
            $keyId = $this->getRequest()->get('keyId',1);
        $timeType = $this->getRequest()->getPost('time_type',1);

        $this->data['keyId'] = $keyId;
        $this->data['time_type'] = $timeType;
        
        $setting = new KeySettingModel();
        $keyValue = $setting->getOne($keyId);
        $keyValue = $keyValue[0];
        $this->data['key'] = $keyValue;

        //获取key相关的维度
        $relate = $setting->getRelateDemension($keyValue['key_en_name']);
        $this->data['demension'] = $relate;

        $this->data['sub_title'] = $keyValue['key_cn_name'];
        $drill = new DrillModel();
        $values = $drill->listAll($keyId, $timeType);

        foreach($values as $k => $v){
            $values[$k]['demension'] = Dict::trans($keyValue['demension_en_name'],$v['demension'],$keyId);
        }

        $this->data['datas'] = $values;

        $this->getView()->display('/keys/drill.html', $this->data);
    }

    public function JqAction(){
        $this->data['sub_cate'] = '指标数据';
        
        $keyId = $this->getRequest()->getPost('demension');
        
        if(empty($keyId))
            $keyId = $this->getRequest()->get('keyId',1);
        $timeType = $this->getRequest()->getPost('time_type',1);

        $this->data['keyId'] = $keyId;
        $this->data['time_type'] = $timeType;
        
        $setting = new KeySettingModel();
        $keyValue = $setting->getOne($keyId);
        $keyValue = $keyValue[0];
        $this->data['key'] = $keyValue;

        //获取key相关的维度
        $relate = $setting->getRelateDemension($keyValue['key_en_name']);
        $this->data['demension'] = $relate;

        $this->data['sub_title'] = $keyValue['key_cn_name'];
        $drill = new DrillModel();
        $values = $drill->listAll($keyId, $timeType,'asc');

        $result = array(
            'timeline' => array(),
            'series' => array(),
            'legend' => array()
        );

        $timeLine = $this->getTimeLine($timeType);
        $result['timeline'] = $timeLine;

        $tmpArr = array();
        foreach($values as $k => $v){
            $values[$k]['demension'] = Dict::trans($keyValue['demension_en_name'],$v['demension'],$keyId);
            $tmpArr[$v['start_time']][$values[$k]['demension']] = $v['values'];
            in_array($values[$k]['demension'],$result['legend']) ? '' : $result['legend'][] = $values[$k]['demension'];
        }
        foreach($timeLine as $k => $v){
            foreach($result['legend'] as $kk => $vv){
                if(!key_exists($v,$tmpArr)){
                    $result['series'][$kk]['name'] = $vv;
                    $result['series'][$kk]['value'][] = 0;
                }else if(isset($tmpArr[$v][$vv])){
                    $result['series'][$kk]['name'] = $vv;
                    $result['series'][$kk]['value'][] = $tmpArr[$v][$vv];
                }else{
                    $result['series'][$kk]['name'] = $vv;
                    $result['series'][$kk]['value'][] = 0;
                }
            }
        }
        echo json_encode($result);
        exit;
    }

    function getTimeLine($timeType){
        $timeline = array();
        switch ($timeType) {
            case 1:   //day
                $start = date('Y-m-d',strtotime('2015-01-01'));
                $end = date('Y-m-d',strtotime("-1 days"));
                while($start <= $end){
                    $timeline[] = $start;
                    $start = date('Y-m-d',strtotime("$start +1 day"));
                }
                break;
            case 2:     //week
                $start = date('Y-m-d',strtotime('2015-01-05'));
                $end = date('Y-m-d',strtotime("-1 week"));
                while($start <= $end){
                    $timeline[] = $start;
                    $start = date('Y-m-d',strtotime("$start +1 week"));
                }
                break;
            default:   //month
                $start = date('Y-m-d',strtotime('2015-01-01'));
                $end = date('Y-m-d',strtotime("-1 months"));
                while($start <= $end){
                    $timeline[] = $start;
                    $start = date('Y-m-d',strtotime("$start +1 months"));
                }
                break;
        }
        return $timeline;
    }
}
