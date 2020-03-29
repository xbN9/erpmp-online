<?php
use XHProf\Runs\XHProfRunsDefault as XHProf;
use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use Yaf\Registry;

class XHProfPlugin extends Plugin_Abstract{
//	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
//		/* 在路由之前执行,这个钩子里，你可以做url重写等功能 */
////		var_dump("routerStartup");
//	}
//	public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
//		/* 路由完成后，在这个钩子里，你可以做登陆检测等功能*/
////		var_dump("routerShutdown");
//	}
//	public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
////		var_dump("dispatchLoopStartup");
//	}
//	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
////		var_dump("preDispatch");
//	}
//	public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
////		var_dump("postDispatch");
//	}
//	public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
//		/* final hoook
//		   in this hook user can do loging or implement layout */
//		var_dump("dispatchLoopShutdown");
//	}
	/**
	 * 在yaf路由分发之后响应正文之前，保存XHProf的性能统计数据
	 * @access public
	 * @param Request_Abstract $request
	 * @param Response_Abstract $response
	 * @return void
	 */
	// public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response){
	public function routerShutdown(Request_Abstract $request, Response_Abstract $response){
        $config = Registry::get('config');
		if (isset($config['xhprof'])) {
			$xhprof_config = $config['xhprof'];
			if (extension_loaded('xhprof') &&  $xhprof_config['open']==1)
			{
				$namespace = $xhprof_config['namespace'] ? $xhprof_config['namespace'] : 'DefaultNameSpace';
				$xhprof_data = xhprof_disable();
				$xhprof_runs = new XHProf();
				$run_id = ucfirst($request->controller) .'_'. ucfirst($request->action). '-' .str_replace('.', '', (string) microtime(true));
				$xhprof_runs->save_run($xhprof_data, $namespace, $run_id);
			}
		}
	}
}
