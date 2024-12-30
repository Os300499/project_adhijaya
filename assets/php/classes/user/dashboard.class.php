<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
class Dashboard extends _Controller{
	
	function __construct(){
		parent::__construct();
	}

	public function index(){
		if(isset($this->view)){
			echo 'a';
		}
		else{
			echo 't';
		}
	}
}