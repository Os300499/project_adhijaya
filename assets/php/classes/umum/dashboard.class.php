<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Dashboard extends _Controller{
	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->view->template('template');
		$this->view->setDir('dashboard');
		$this->view->templateShow(['content'=>'']);
	}
}