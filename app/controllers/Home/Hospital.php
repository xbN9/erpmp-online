<?php

// use Yaf\Controller_Abstract;
// use Yaf\Registry;
// use Yaf\Session;
use Yaf\Dispatcher;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Home_HospitalController extends CommonController
{
    public function init()
    {
        parent::init();
    }
    
    public function listAction()
    {
        $data = [];
        $res = parent::$db_resouce->getRepository("HospitalModel")->findBy([]);
        if ($res) {
            foreach ($res as $k => $val) {
                $data[$k]['id'] =$val->getHospitalId();
                $data[$k]['name'] =$val->getHospitalName();
                $data[$k]['url'] =$val->getHospitalUrl();
            }
        }
        // $this->display('list', $data);
        $this->getView()->assign('data', $data);
    }

    public function detailAction()
    {
    }
}
