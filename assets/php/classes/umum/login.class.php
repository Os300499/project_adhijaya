<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Login extends _Controller{
	function __construct(){
		parent::__construct();
		$con = $this->database->mysqli;
		$this->mysql->setCon($con);
	}

	public function index(){
		$this->view->setDir('Login');
		$this->view->show(['login'=>'']);
	}


	public function sing_in(){
		jsonheader();
		$pass = $_POST['password'];
		$username = $_POST['username'];
		$cek = $this->mysql->Ambilkondisi(['password'=>'=','username'=>'=',],["s.$pass","s.$username"],null,'users');
		if($cek->num_rows > 0){
			$res = ['status'=>'success','pesan'=>'','body'=>''];
		}else{
			$res = ['status'=>'error','pesan'=>'','body'=>''];
		}
		echo json_endcode($res);
	}
}