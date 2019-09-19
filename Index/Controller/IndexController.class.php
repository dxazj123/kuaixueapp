<?php
	class IndexController extends Controller{
		public function index(){
			// echo '欢迎使用HD框架：）';
			$Code = new Code();
			$Code->show();
		}
	}
?> 			