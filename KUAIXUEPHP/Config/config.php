<?php 

return array(
	//验证码位数
	'CODE_LEN' => 4,
	//默认时区
	'DEFAULT_TIME_ZONE' => 'PRC',
	//session自动开启
	'SESSION_AUTO_START' => TRUE,
	'VAR_ACTION' => 'a',
	'VAR_CONTROLLER' => 'c',
	//是否开启日志
	'SAVE_LOG' =>  true,
	'ERROR_URL' => '',
	'ERROR_MSG' => 'website error',
	//自动加载Common/Lib目录下的文件，可以载入多个
	'AUTO_LOAD_FILE' => array(),
	'DB_CHARSET' => 'utf8',
	'DB_HOST' => '127.0.0.1',
	'DB_PORT' => 3306,
	'DB_USER' => 'root',
	'DB_PASSWORD' => '',
	'DB_DATABASE' => '',
	'DB_PERFIX' => ''
	);