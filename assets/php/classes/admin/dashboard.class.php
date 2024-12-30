<?php 
// https://domain.com/admin/dashboard/
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
class Dashboard extends _Controller{
	
	function __construct(){
		parent::__construct();
	}

	public static function index(){
		echo 'ini Dashboard admin';
	}
}