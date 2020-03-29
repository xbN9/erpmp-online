<?php
namespace xhprof;

use Yaf\Dispatcher;
use Yaf\Registry;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;

use XHProf\Display\XHProfDisplay;
use XHProf\Runs\XHProfLib;
use XHProf\Runs\XHProfRunsDefault;
use XHProf\Runs\CallgraphUtils;

class Admin_XhprofController extends Admin_BaseController
{
    public $run;
    public $run1;
    public $run2;
    public $source;
    public $wts;      
    public $symbol;   
    public $sort;     
    public $func;     
    public $critical;     
    private $params = [
            'run'       => ['xhprof_string_param', ''],
            'source'    => ['xhprof_string_param', 'xhprof'],
			'wts'       => ['xhprof_string_param', ''],
			'symbol'    => ['xhprof_string_param', ''],
			'sort'      => ['xhprof_string_param', 'wt'], // wall time
			'run1'      => ['xhprof_string_param', ''],
			'run2'      => ['xhprof_string_param', ''],
			'all'       => ['xhprof_uint_param', 0],
            'func'      => ['xhprof_string_param', ''],
            'type'      => ['xhprof_string_param', 'png'],
            'threshold' => ['xhprof_float_param', 0.01],
            'critical'  => ['xhprof_bool_param', true],
        ];

    public function init(){
        ini_set('max_execution_time', 100);

        $this->run      = $this->getRequest()->get('run');
        $this->run1     = $this->getRequest()->get('run1');
        $this->run2     = $this->getRequest()->get('run2');
        $this->source   = $this->getRequest()->get('source'); 
        $this->wts      = $this->getRequest()->get('wts'); 
        $this->symbol   = $this->getRequest()->get('symbol'); 
        $this->sort     = $this->getRequest()->get('sort'); 
        $this->func     = $this->getRequest()->get('func');
        $this->critical = $this->getRequest()->get('critical');

    }

    public function listAction(){
        $file_path = PUBLIC_PATH.'/../tmp/profiles/';
        $this->data['files'] = $this->fileList($file_path);
        $this->getView()->assign('data', $this->data);
    }

    public function showProfileAction(){

        XHProfLib::xhprof_param_init($this->params);
		foreach ($params as $k => $v) {
			$params[$k] = $$k;
			if ($params[$k] == $v[1]) {
				unset($params[$k]);
			}
		}
        
        //TODO 使用table
        $static_dir = Registry::get('config')['xhprof']['static_dir'];
        XHProfDisplay::xhprof_include_js_css($static_dir);
		$xhprof_runs_impl = new XHProfRunsDefault();
        $display = new XHProfDisplay('/admin/system/showProfile');
        $display::displayXHProfReport($xhprof_runs_impl, $this->params, $this->source, $this->run, $this->wts,$this->symbol, $this->sort, $this->run1, $this->run2);
    }

    public function callgraphAction(){
        $image_types = [
            "jpg" => 1,
            "gif" => 1,
            "png" => 1,
            "svg" => 1, // support scalable vector graphic
            "ps" => 1,
        ];
        XHProfLib::xhprof_param_init($this->params);
        if ($threshold < 0 || $threshold > 1) {
            $threshold = $params['threshold'][1];
        }
        if (!array_key_exists($type, $image_types)) {
            $type = $params['type'][1]; 
        }
        $xhprof_runs_impl = new XHProfRunsDefault();
        if (!empty($this->run)) {
            CallgraphUtils::xhprof_render_image($xhprof_runs_impl, $this->run, $type,
                $threshold, $this->func, $this->source, $this->critical);
        } else {
            CallgraphUtils::xhprof_render_diff_image($xhprof_runs_impl, $this->run1, $this->run2,
                $type, $threshold, $this->source);
        }
    }
}
