<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Awal extends _Controller{
	function __construct(){
		parent::__construct();
	}

	public function index(){
		if (isset($_session['iduser'])) {
			$this->view->pindah('Dashboard');
		}else{
			$this->view->pindah('login');
		}
	}
}

