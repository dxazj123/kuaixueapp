<?php 
	final class KUAIXUEPHP{
		public static function run(){
			self::_set_const();
			defined('DEBUG') || define('DEBUG',false);
			if(DEBUG){
				self::_create_dir();
				self::_import_file();
			}else{
				error_reporting(0);
				require_once TEMP_PATH .'/~boot.php';
			}
			Application::run();
		}
		/**
		 * 创建框架所需常量
		 */
		private static function _set_const(){
			$path = str_replace('\\', '/', __FILE__);
			define('KUAIXUEPHP_PATH',dirname($path));
			define('CONFIG_PATH',KUAIXUEPHP_PATH.'/Config');
			define('DATA_PATH',KUAIXUEPHP_PATH.'/Data');
			define('LIB_PATH',KUAIXUEPHP_PATH.'/Lib');
			define('CORE_PATH',LIB_PATH.'/Core');
			define('FUNCTION_PATH',LIB_PATH.'/function');

			define('EXTEND_PATH',KUAIXUEPHP_PATH . '/Extends');
			define('TOOL_PATH',EXTEND_PATH . '/Tool');
			define('ORG_PAHT',EXTEND_PATH . '/Org');

			define('ROOT_PATH',dirname(KUAIXUEPHP_PATH));
			//临时目录
			define('TEMP_PATH',ROOT_PATH . '/Temp' );
			//日志目录
			define('LOG_PATH',TEMP_PATH . '/Log');
			define('APP_PATH', ROOT_PATH.'/'.APP_NAME);
			define('APP_CONFIG_PATH',APP_PATH.'/Config');
			define('APP_CONTROLLER_PATH', APP_PATH.'/Controller');
			define('APP_TPL_PATH', APP_PATH.'/Tpl');
			define('APP_PUBLIC_PATH', APP_TPL_PATH.'/Public');
			define('KUAIXUEPHP_VERSION', '1.0');
			//创建公共
			define('COMMON_PATH',ROOT_PATH . '/Common');
			//公共配置项文件夹
			define('COMMON_CONFIG_PATH', COMMON_PATH . '/Config');
			//公共模型文件夹
			define('COMMON_MODEL_PATH', COMMON_PATH . '/Model');
			//公共库文件夹
			define('COMMON_LIB_PATH', COMMON_PATH . '/Lib');

			define('IS_POST',($_SERVER['REQUEST_METHOD'] == 'POST' ? true : false));
			if(isset($_SERVER['HTTP_X_REQUEST_WITH']) && $_SERVER['HTTP_X_REQUEST_WITH'] == 'XMLHttpRequest'){
				define('IS_AJAX',true);
			}else{
				define('IS_AJAX',false);
			}
			
		}
		/**
		 * 创建应用目录
		 */
		private static function _create_dir(){
			$arr = array(
				COMMON_CONFIG_PATH,
				COMMON_MODEL_PATH,
				COMMON_LIB_PATH,
				APP_PATH,
				APP_CONFIG_PATH,
				APP_CONTROLLER_PATH,
				APP_TPL_PATH,
				APP_PUBLIC_PATH,
				TEMP_PATH,
				LOG_PATH
			);

			foreach ($arr as $v) {
				is_dir($v) || mkdir($v,0777,true);
			}

			is_file(APP_TPL_PATH . '/success.html') || copy(DATA_PATH . '/Tpl/success.html',APP_TPL_PATH . '/success.html');
			is_file(APP_TPL_PATH . '/error.html') || copy(DATA_PATH . '/Tpl/error.html',APP_TPL_PATH . '/error.html');
		}
		/**
		 * 载入框架所需文件
		 */
		private static function _import_file(){
			$fileArr =  array(
				CORE_PATH . '/Log.class.php',
				FUNCTION_PATH . '/function.php',
				CORE_PATH . '/Controller.class.php',
				CORE_PATH . '/Application.class.php'

			);
			$str = '';
			foreach ($fileArr as $v) {
				$str .= trim(substr(file_get_contents($v), 5, -2));
				
				require_once $v;
			}

			$str = "<?php\r\n" .$str;

			file_put_contents(TEMP_PATH . '/~boot.php', $str) || die('access not allow');
		}
	}

	KUAIXUEPHP::run();
 ?>