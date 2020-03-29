<?php

/**
 * @filename ActionsLog.php
 * @touch date Mon 27 Apr 2015 11:41:22 AM CST
 * @author doifusd<doifusdsky@gmail.com>
 * @license http://www.zend.com/license/3_0.txt PHP License 3.0
 * @version 1.0.0 
*/

class ActionsLog{
    
    private   $log_type; //日志类型(1:行为日志,2,3,4)
    private   $log_file; //日志存放文件
    private   $log_path; //日志存放目录

    const DEBUG  = 100;
    const INFO   = 75;
    const NOTICE = 50;
    const WARNING =25;
    const ERROR   = 10;
    const CRITICAL = 5;
    const ACTION = 1;   //用户行为日志

    protected function __construct(){

		$cfg = Yaf_Registry::get("config")->log->toArray();
        $this->log_path = $cfg['config']['log_path'];

        if(!isset($this->log_path)){
            throw new Exception('log path not find');
        }

//        if(!is_resource($this->log_file)){
//            throw new Exception('there is no file');
//        }
        
    }

    public static function getInstance(){
        static $obj;
        if(!isset($obj)){
            $obj = new ActionsLog();
        }
        return $obj;
    }


    public function LogMessage($msg, $logLevel = self::ACTION,$module = null){
        //每天生成一个文件
        $this->log_file = date('Y-m-d H:i:s').'action_log.txt';
        $source = @fopen($this->log_path.'/'.$this->log_file,'a+');

        $time = strftime('%x  %X',time());
        $msg = str_replace("\t",'',$msg);
        $msg = str_replace("\n",'',$msg);
        
        $strLogLevel = $this->levelToString($logLevel);
        
//        if(isset($module)){
//            $module = str_replace(array("\n","\t"),array("",""),$module);
//        }
        $logLine = "$time\t$msg\t$strLogLevel\t$module\r\n";
        fwrite($source,$logLine);
    }

    public function levelToString($logLevel){
         $ret = '[unknow]';
         switch ($logLevel){
                case self::DEBUG:
                     $ret = 'DEBUG';
                     break;
                case self::INFO:
                     $ret = 'INFO';
                     break;
                case self::NOTICE:
                     $ret = 'NOTICE';
                     break;
                case self::WARNING:
                     $ret = 'WARNING';
                     break;
                case self::ERROR:
                     $ret = 'ERROR';
                     break;
                case self::ACTION:
                     $ret = 'ACTION';
                     break;
                case self::CRITICAL:
                     $ret = 'CRITICAL';
                     break;
         }
         return $ret;
    }
}
