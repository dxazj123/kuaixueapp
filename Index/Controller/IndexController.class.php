<?php
	class IndexController extends Controller{
		
		public function index(){
			p(IS_AJAX);
			$this->display();
		}
	}
?> 			