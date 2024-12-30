<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Tes extends _Controller{
	/**
	 *  JANGAN DIPINDAHKAN LOKASI DARI FILE INI
	 *  
	 *  DEFINED PADA BARIS 2 HARUS ADA
	 *  untuk keamanan file, tulisan pada baris ke 2 harus ada. karena mencegah pengguna
	 *  mengakses file ini secara langsung
	 *  
	 *  file ini adalah halaman tes
	 *  contoh : https://www.domain.com/tes
	 * 
	 */
	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->view->template('template');
		$this->view->setDir('dashboard');
		$this->view->templateShow(['content'=>'']);
	}

	public function masuk(){
		echo 'ini masuk';
	}

	private function loadHeader($data = ''){
		$this->view->setDir('templates');
		$isi = array('header'=>$data);
		$this->view->show($isi);
	}

	private function loadFooter($data = ''){
		$this->view->setDir('templates');
		$isi = array('footer'=>$data);
		$this->view->show($isi);
	}
}