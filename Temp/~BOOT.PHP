<?php
class Log{
		static public function write($msg,$level='ERROR',$type=3,$dest=NULL){
			if(!C('SAVE_LOG')) return;
			if(is_null($dest)){
				$dest = LOG_PATH . '/' .date('Y_m_d') . '.log';
			}

			if(is_dir(LOG_PATH)) error_log("[TIME]:". date('Y-m-d H:i:s') . " {$level} : {$msg}\r\n",$type,$dest);
		}
	}function halt($error,$level='ERROR',$type=3,$dest=NULL){
		if(is_array($error)){
			Log::write($error['message'],$level,$type,$dest);
		}else{
			Log::write($error,$level,$type,$dest);
		}

		$e = array();

		if(DEBUG){
			if(!is_array($error)){
				$trace = debug_backtrace();

				$e['message'] = $error;
				$e['file'] = $trace[0]['file'];
				$e['line'] = $trace[0]['line'];
				$e['class'] = isset($trace[0]['class']) ? $trace[0]['class'] : '';
				$e['function'] = isset($trace[0]['function']) ? $trace[0]['function'] : '';
				ob_start();
				debug_print_backtrace();
				$e['trace'] = htmlspecialchars(ob_get_clean());
			}else{
				$e = $error;
			}
		}else{
			if($url = C('ERROR_URL')){
				go($url);
			}else{
				$e['message'] = C('ERROR_MSG');
			}
		}

		include DATA_PATH . '/Tpl/halt.html';
	}
	function p($arr){
		if(is_bool($arr)){
			var_dump($arr);
		}elseif(is_null($arr)) {
			var_dump(NULL);
		}else{
			echo "<pre style='padding:10px;border_radius:5px;background:#f5f5f5;border:1px solid #ccc;font-size:14px;'>";
			print_r($arr);
			echo "</pre>";
		}	
	}

	function go($url,$time=0,$msg=''){

		if(!headers_sent()){
			$time ==0 ? header('Location:'.$url) : header("refresh:{$time};url={$url}");
			die($msg);
		}else{
			echo "<meta http-equiv='Refresh' content='{$time}; URL={$url}'>";
			if($time) die($msg);
		}
	}
	//1.加载配置项
	//C($sysConfig) C($userConfig)
	//2.读取配置项
	//C('CODE_LEN')
	//3.临时动态改变配置项
	//C('CODE_LEN',20)
	//4.C();
 	function C($var = NULL, $value = NULL){
 		static $config = array();
 		//加载配置项
 		if(is_array($var)){
 			$config = array_merge($config,array_change_key_case($var,CASE_UPPER));
 			return;
 		}
 		//读取或者动态改变配置项
 		if(is_string($var)){
 			$var = strtoupper($var);

 			if(!is_null($value)){
 				$config[$var] = $value;
 				return;
 			}

 			return isset($config[$var]) ? $config[$var] : NULL;
 		}

 		if(is_null($var) && is_null($value)){
 			return $config;
 		}
 	}/**
 * 父类controller
 */
	class Controller{

		public function __construct(){
			echo '父类必须执行构造方法';
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
	}final class Application{
 		public static function run(){
 			self::_init();
 			self::_set_url();
 			spl_autoload_register(array(__CLASS__,'_autoload'));
 			self::_create_demo();
 			self::_app_run();
 		}
 		/**
 		 * 实例化应用控制器
 		 */
 		private static function _app_run(){
 			$c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';
 			$a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'index';

 			$c .= 'Controller';

 			$obj = new $c();

 			$obj->$a();
 		}
 		/**
 		 * 创建默认控制器
 		 */
 		private static function _create_demo(){
 			$path = APP_CONTROLLER_PATH . '/IndexController.class.php';
 			$str = <<<str
<?php
	class IndexController extends Controller{
		public function index(){
			echo 'OK';
		}
	}
?> 			
str;
			is_file($path) || file_put_contents($path,$str);
 		}
 		/**
 		 * 自动载入功能
 		 */
 		private static function _autoload($className){
 			include APP_CONTROLLER_PATH . '/' .$className . '.class.php';
 		}
 		/**
 		 * 设置外部路径
 		 */
 		private static function _set_url(){
 			// p($_SERVER);
 			$path = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
 			$path = str_replace('\\','/', $path);
 			define('__APP__', $path);
 			define('__ROOT__',dirname(__APP__));
 			define('__TPL__',__ROOT__ . '/' . APP_NAME . '/Tpl');
 			define('__PUBLICE__',__TPL__ . '/Public');
 		}
 		/**
 		 * 初始化框架
 		 */
 		private static function _init(){
 			//加载配置项
 			C(include CONFIG_PATH . '/config.php');
 			$userPath = APP_CONFIG_PATH . '/config.php';

 			$userConfig = <<<str
<?php
return array(
	//配置项=> 配置值
	);	
?>		
str;
			is_file($userPath) || file_put_contents($userPath,$userConfig);
			//加载用户配置项
			C(include $userPath);
			//设置默认时区
			date_default_timezone_set(C('DEFAULT_TIME_ZONE'));
			C('SESSION_AUTO_START') && session_start();
 		}
 		
 	}