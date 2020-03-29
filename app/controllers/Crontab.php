<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/21
 * Time: 17:10
 */

class CrontabController extends Yaf_Controller_Abstract {

    public function init() {
        Yaf_Dispatcher::getInstance()->disableView(0);
    }

    public function indexAction() {

        if ($this->getRequest()->get('start') || $this->getRequest()->get('stop')) {
            $select['start'] = $this->getRequest()->get('start');
            $select['stop']  = $this->getRequest()->get('stop');
        }
    
        if ($this->getRequest()->get('start_cu') || $this->getRequest()->get('stop_cu')) {
            $select['start_cu'] = $this->getRequest()->get('start_cu');
            $select['stop_cu']  = $this->getRequest()->get('stop_cu');
        }

        $files['name'] = $this->getRequest()->get('filename');
        $files['ext']  = $this->getRequest()->get('ext');

        $finance = new FinanceModel();
        if ($this->getRequest()->get('type') == 2) {
            $finance->CrontotalTable($select, $files);
        } else {
            $finance->detailData($select, $files);
        }
    }

}