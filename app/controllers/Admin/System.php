<?php
use Yaf\Session;
use Yaf\Dispatcher;
use Yaf\Registry;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;
use XHProf\Display\XHProfDisplay;
use XHProf\Runs\XHProfLib;
use XHProf\Runs\XHProfRunsDefault;
use XHProf\Runs\CallgraphUtils;

class Admin_SystemController extends Admin_BaseController
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
    public $tmp = array();

    public function init()
    {
        parent::init();
        $this->data['sub_cate'] = '系统管理';
        //获取当前请求地址
        
        $this->run      = $this->getRequest()->get('run');
        $this->run1     = $this->getRequest()->get('run1');
        $this->run2     = $this->getRequest()->get('run2');
        $this->source   = $this->getRequest()->get('source'); 
        $this->wts      = $this->getRequest()->get('wts'); 
        $this->symbol   = $this->getRequest()->get('symbol'); 
        $this->sort     = $this->getRequest()->get('sort'); 
        $this->func     = $this->getRequest()->get('func');
        $this->critical = $this->getRequest()->get('critical');

        XHProfLib::xhprof_param_init($this->params);
    }

    public function  listAction(){
        $file_path = PUBLIC_PATH.'/../tmp/profiles/';
        $this->data['files'] = $this->fileList($file_path);
        $this->data['profile_url'] = '/admin/system/showProfile/callgraph?';
        $this->getView()->assign('data', $this->data);
    }

    public function showProfileAction(){
		foreach ($this->params as $k => $v) {
			$this->params[$k] = $$k;
			if ($this->params[$k] == $v[1]) {
				unset($this->params[$k]);
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
        $threshold = 2;
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

    public function fileList($path)
    {
        $data = [];
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file!='.' && $file!='..' && $file[0]!='.') {
                        $file_path = $path.$file;
                        if (is_file($file_path)) {
                             $tmp = [
                                 'name'=>$file,
                                 'file'=>$file_path,
                             ];
                             array_push($data, $tmp);
                        } elseif (is_dir($file_path)) {
                             $this->fileList($file_path);
                        }
                    }
                }
                closedir($dh);
            } else {
                return false;
            }
        } else {
            return false;
        }
        return $data;
    }

    public function indexAction()
    {
        //获取磁盘大小
        $this->data['disk_total'] = round(disk_total_space("/")/(1024*1024*1024), 2);
        //剩余磁盘大小
        $this->data['disk_space'] = round(disk_free_space("/")/(1024*1024*1024), 2);
        $this->data['disk_url'] = '/system/disk';

        //CPU使用

        //获取内存大小,(TODO 集群内存计算)
        // $this->getUsed();
        $this->data['memory_total'] = round(disk_total_space("/")/(1024*1024*1024), 2);
        //剩余内存大小
        // $this->data['memory_space'] = round(disk_free_space("/")/(1024*1024*1024), 2);
        // $this->data['memory_url'] = '/system/memory';

        //获取网速
        //mysql 磁盘大小,内存大小,进程个数
        //php 同理
        //nginx  同理
        //cache(redis,memcache) 同理
        $this->getView()->assign('data', $this->data);
    }

    public function jobListAction(){
        exec('crontab -l',$output);
        if(!empty($output)){
            $cron = [];
            foreach($output as $k=>$val){
                $tmp_arr = explode(' ',$val); 
                if($val[0]=='#'){
                    $cron[$k]['status'] = '关闭';
                    $minute = substr($tmp_arr[0],1); 
                }else{
                    $cron[$k]['status'] = '开启';
                }
                $cron[$k]['minute'] = $minute;
                $cron[$k]['hour'] = $tmp_arr[1];
                $cron[$k]['day'] = $tmp_arr[2];
                $cron[$k]['month'] = $tmp_arr[3];
                $cron[$k]['week'] = $tmp_arr[4];
                $cron[$k]['comment'] = join(' ',array_slice($tmp_arr,5));
                
            }
        }
        $this->data['table_content'] = $cron;
		$this->data['table_title'] = ['状态','分钟','小时','天','月','星期','备注'];
        $this->getView()->assign('data', $this->data);
    }

    //磁盘详情,占用空间最大的前100
    public function diskAction()
    {
    }

    /*private function getUsed()
    {
       //mem
        die(var_dump($retval));
        $rs = "";
        while (!feof($fp)) {
            $rs .= fread($fp, 1024);
        }
        $tast_info = explode(",", $sys_info[3]);//进程 数组
        $cpu_info = explode(",", $sys_info[4]);  //CPU占有量  数组
        $mem_info = explode(",", $sys_info[5]); //内存占有量 数组
        //正在运行的进程数
        $tast_running = trim(trim($tast_info[1], 'running'));

        //CPU占有量
        $cpu_usage = trim(trim($cpu_info[0], 'Cpu(s): '), '%us');  //百分比
        //内存占有量
        $mem_total = trim(trim($mem_info[0], 'Mem: '), 'k total');
        $mem_used = trim($mem_info[1], 'k used');
        $mem_usage = round(100*intval($mem_used)/intval($mem_total), 2);  //百分比

        $fp = popen('df -lh | grep -E "^(/)"', "r");
        $rs = fread($fp, 1024);
        pclose($fp);
        $rs = preg_replace("/\s{2,}/", ' ', $rs);  //把多个空格换成 “_”
        $hd = explode(" ", $rs);
        $hd_avail = trim($hd[3], 'G'); //磁盘可用空间大小 单位G
        $hd_usage = trim($hd[4], '%'); //挂载点 百分比
          //print_r($hd);
          // $fp = popen("date +"%Y-%m-%d %H:%M"", "r");
          // $rs = fread($fp, 1024);
          // pclose($fp);
          // $detection_time = trim($rs);
          // return array('cpu_usage'=>$cpu_usage,'mem_usage'=>$mem_usage,'hd_avail'=>$hd_avail,'hd_usage'=>$hd_usage,'tast_running'=>$tast_running,'detection_time'=>$detection_time);
    }*/
}
