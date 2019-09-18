<?php
/**
 * 父类controller
 */
	class Controller{

		public function __construct(){
			
			if(method_exists($this,'__init')){
				$this->__init();
			}
			if(method_exists($this,'__auto')){
				$this->__auto();
			}
		}
		/**
		 * 成功提示方法
		 */
		protected function success($msg,$url=NULL,$time=3){
			$url = $url ? "window.location.href='" . $url . "'" : "window.history.back(-1)";
			include APP_TPL_PATH . '/success.html';
			die;
		}
		protected function error($msg,$url=NULL,$time=3){
			$url = $url ? "window.location.href='" . $url . "'" : "window.history.back(-1)";
			include APP_TPL_PATH . '/error.html';
			die;
		}
	}
?>