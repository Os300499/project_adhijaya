<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
#[AllowDynamicProperties]
class Simulasi{
	/**
	 *  Class Simulasi
	 * 
	 *  Digunakan untuk simulasi halaman html beserta data
	 *  yang nantinya akan digunakan bersama dengan class
	 *  asli dari php classes dan koneksi database asli
	 * 
	 *  untuk keamanan, simulasi tidak akan jalan pada protokol
	 *  https. 
	 * 
	 */
	public static $jalan = false;
	private static $template = '', 
	$daftarFile = [], 
	$listClass = [], 
	$database = false, 
	$jkolom = 1,
	$jdata = 1;

	public function __construct(){
		static::cekFile();
	}

	/**
	 *  fungsi untuk mengecek file simulasi_url yang nanti
	 *  akan jadi link asli yang dapat diakses bersama
	 *  ketika disatukan dengan class pada php class
	 * 
	 */
	private static function cekFile(){
		try{
			$file = 'simulasi_url';
			if(PROTOCOLS === 'http'){
				if(is_file($file) && file_exists($file)){
					static::ambilIsi($file);
				}
				else{
					static::buatFileSimulasi();
				}
			}
			else{

			}
		}
		catch(Exception $e){
			// var_dump($e);
		}
	}

	private static function ambilIsi($file){
		try {
			$hasil = [];
			$isi = file($file, FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
			if($isi && !empty($isi)){
				foreach($isi AS $baris => $text){
					if(strpos(trim($text), '#') === 0){
						continue;
					}
					$spos = strpos(trim($text), '=');
					if((int)$spos > -1){
						$a = explode('=', trim($text));
						if(sizeof($a) == 2){
							if(empty(trim($a[1]))){
								continue;
							}
							if(!isset($hasil[trim($a[0])]) && !empty(trim($a[1]))){
								$hasil[trim($a[0])] = trim($a[1]);
							}
							else{
								trigger_error("kesalahan penulisan link simumasi !\n1 link hanya untuk 1 file !\nfile $a[0] memiliki 2 link", E_USER_ERROR);
							}
						}
						else{
							trigger_error("kesalahan penulisan link simumasi !\nlink seharusnya : \nfolder/namaFile = link/ke/halaman/tampilan", E_USER_ERROR);
						}
					}
				}
				if(isset($_GET[0]) && strtolower($_GET[0]) === 'core'){
					die("HAYOoo.. mau ngapain? (-_- ')");
				}
				static::cekIsi($hasil);
			}
		} 
		catch (Exception $e) {
		}
	}

	private static function cekIsi(?array $isi){
		try {
			$status = ['jalan', 'ya', 'run', 'true', 'active', 'go', 'yes'];
			$text = ['status', 'templates', 'database', 'kolom', 'data'];
			if(!empty($isi)){
				foreach($isi AS $path => $link){
					if(in_array(strtolower(trim($path)), $text)){
						if(strtolower(trim($path)) === 'status' && in_array(strtolower(trim($link)), $status)){
							self::$jalan = true;
						}
						elseif(strtolower($path) === 'templates'){
							if(!empty($link)){
								$file = 'page'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$link;
								$exnama = explode(DIRECTORY_SEPARATOR, $file);
								$nama = strtr($exnama[sizeof($exnama)-1], array('.view.php'=>''));
								self::$template = $nama;
							}
							else{
								continue;
							}
						}
						elseif(strtolower(trim($path)) === 'database' && in_array(strtolower(trim($link)), $status) && !empty(trim($link)) && in_array(trim($link), $status)){
							self::$database = true;
						}
						elseif(strtolower(trim($path)) === 'kolom' && intval(trim($link)) > -1){
							self::$jkolom = intval(trim($link));
						}
						elseif(strtolower(trim($path)) === 'data' && intval(trim($link)) > -1){
							self::$jdata = intval(trim($link));
						}
					}
					elseif(!empty($path)){
						$filepath = static::perbaikiPath($path);
						if(!empty($filepath)){
							self::$daftarFile[$filepath] = $link;
						}
						else{
							trigger_error("penulisan path $path tidak sesuai !\nGunakan '\\' atau '/' untuk memisahakan antara folder dan file", E_USER_ERROR);
						}
					}
					else{
						trigger_error("$path tidak diketahui !", E_USER_ERROR);
					}
				}
			}
		} 
		catch (Exception $e) {
			
		}
	}

	private static function perbaikiPath($path){
		$a = strpos($path, '/');
		$b = strpos($path, '\\');
		if($a > -1){
			$c = explode('/', $path);
		}
		elseif($b > -1){
			$c = explode('\\', $path);
		}
		else{
			$c = null;
		}
		$f = '';
		if(!empty($c) || $c !== null){
			foreach($c AS $d => $e){
				if($d == 0){
					$f .= $e;
				}
				else{
					$f .= DIRECTORY_SEPARATOR.$e;
				}
			}
		}
		return $f;
	}

	private static function buatFileSimulasi(){
		$file = 'simulasi_url';
		if(!is_file($file) && !file_exists($file)){
			$isi = "# File Simulasi Tampilan Menggunakan URL / Link tanpa harus membuat class dan menggunakan data asli dari database atau data semu\n\n";
			$isi .= "# PENTING \n";
			$isi .= "# Simulasi Tampilan HANYA BERJALAN PADA PROTOKOL HTTP\n\n";
			$isi .= "# status digunakan untuk menjalankan simulasi\n";
			$isi .= "# nilai dari status yang bisa digunakan untuk menjalankan simulasi adalah : 'jalan', 'ya', 'run', 'true', 'active', 'go', 'yes' (tanpa tanda petik satu (' ') )\n";
			$isi .= "# contoh : status=jalan | status=run | status=ya | status=active\n\n";
			$isi .= "# jika tidak ingin menjalankan simulasi, hapus nilai dari status\n";
			$isi .= "# contoh : status=\n\n";
			$isi .= "status=\n\n";
			$isi .= "# templates digunakan untuk menentukan halaman template yang akan digunakan\n";
			$isi .= "# file halaman template harus berada dalam folder page/templates, dan menggunakan .view.php untuk extensi nama file\n";
			$isi .= "# contoh nama file template : template.view.php\n";
			$isi .= "# jika nama file tidak menggunakan .view.php, file template akan diabaikan\n";
			$isi .= "# untuk penggunaan template, hanya perlu awal dari nama file, tanpa .view.php\n";
			$isi .= "# contoh penggunaan template : templates = template\n\n";
			$isi .= "templates=\n\n";
			$isi .= "# link simulasi akan terhitung dari domain utama.\n";
			$isi .= "# contoh : 'link/yang/akan/digunakan' akan menjadi 'https://domain.com/link/yang/akan/digunakan\n\n";
			$isi .= "# file dari halaman yang akan disimulasi harus berada dalam folder page\n";
			$isi .= "# dan memiliki extensi .view.php, jika tidak, file tersebut akan diabaikan\n";
			$isi .= "# contoh : folder/namaFile = link/yang/akan/digunakan\n\n";
			$isi .= "# jika tidak ada file dan link untuk disimulasikan, maka akan ditampilkan halaman error er_404.php pada folder error\n";
			$isi .= "# untuk halaman awal atau http://domain.com/ menggunakan seperti dibawah\n# folder/namaFile = /\n\n\n";
			$isi .= "# DATA\n\n";
			$isi .= "# database digunakan untuk mengambil tabel dan kolom pada database\n";
			$isi .= "# nilai yang dapat digunakan sama dengan nilai yang dapat digunakan pada status\n\n";
			$isi .= "database=\n\n";
			$isi .= "# koneksi ke database dapat diatur pada file pengaturan.php\n";
			$isi .= "# jika koneksi ke database lebih dari 1, untuk mengambil isi\n# dari setiap koneksi menggunakan \$data['con0'] untuk koneksi pertama. \n# \$data['con1'] untuk koneksi kedua. dan seterusnya\n";
			$isi .= "# jika koneksi ke database tidak ada, maka akan tabel dan kolom yang digunakan adalah tabel dan kolom bawaan yang diatur dibawah\n";
			$isi .= "# jumlah table bawaan adalah 1. untuk kolomnya dapat diatur dibawah\n\n";
			$isi .= "kolom=1\n\n";
			$isi .= "# untuk pengambilan data dapat menggunakan variable \$data.\n";
			$isi .= "# jika koneksi ke database tidak ada, maka menggunakan \$data['table'] untuk mengambil isi data semu.\n";
			$isi .= "# jika koneksi ada, dan maka akan menggunakan tabel dan kolom dari database\n";
			$isi .= "# jumlah data yang akan digunakan dapat diatur dibawah\n\n";
			$isi .= "data=\n\n";
			$isi .= "# CATATAN \n";
			$isi .= "# jika koneksi ke database ada, dan tabel tidak ada. maka akan menggunakan data semu\n";

			file_put_contents($file, $isi);
		}
	}

	public static function jalankan(){
		if(isset($_GET)){
			if(isset($_GET[0]) && $_GET[0] === 'assets'){
				$minta = takeClass(htmlspecialchars(stripslashes(strip_tags($_GET[0]))));
				$method = htmlspecialchars(stripslashes(strip_tags($_GET[1])));
				array_shift($_GET);
				array_shift($_GET);
				$isi = implode('/', $_GET);
				$minta->ambilAsset($method, $isi);
			}
			foreach($_GET AS $a => $b){
				unset($_GET[$a]);
			}
		}
		foreach (loaded_class() as $nama => $class) {
			self::$listClass[$nama] = $class;
		}
		$req = static::checkUrl();
		if(empty($req)){
			$path = array_search('/', self::$daftarFile);
		}
		else{
			$path = array_search($req, self::$daftarFile);
		}
		static::checkPath($path);
	}

	private static function checkPath($path){
		if($path !== false && !empty($path)){
			$c = static::checkDir($path);
			$e = '';
			$f = [];
			for($d = 0; $d < sizeof($c)-1; $d++){
				$f[] = $c[$d];
			}
			$e = implode('/', $f);
			static::render($e, $c['file']);
		}
		else{
			static::notFound();
		}
	}

	private static function render($dir, $file){
		try{
			$a = self::$listClass['view'];
			$b = static::ambilData();
			if(!empty(self::$template)){
				$a->template(self::$template);
				$a->setDir($dir);
				$a->templateShow([$file=>$b]);
			}
			else{
				$a->setDir($dir);
				$a->show([$file=>$b]);
			}
		}
		catch(Exception $e){

		}
	}

	private static function ambilData(){
		$isi = [];
		if(self::$database === true){
			$a = self::$listClass['database']->mysqli;
			if(is_object($a)){
				$isi = static::dataSql($a);
			}
			elseif(is_array($a)){
				foreach ($a as $b => $c) {
					$isi['con'.$b] = static::dataSql($c);
				}
			}
			else{
				$isi = static::dataSemu();
			}
		}
		else{
			$isi = static::dataSemu();
		}

		return $isi;
	}

	private static function dataSql($con){
		$c = [];
		$a = self::$listClass['mysql'];
		$a->setCon($con);
		$b = $a->semuaTable($con);
		$j = (self::$jdata > 1)? self::$jdata:1;
		if(!empty($b)){
			foreach ($b as $d => $e) {
				$f = $a->semuaKolom($e);
				for($g = 0; $g < $j; $g++){
					foreach($f AS $h => $i){
						$c[$e][$g][$i] = $i.'_'.$g;
					}
				}
			}
		}
		else{
			$c = static::dataSemu();
		}
		return $c;
	}

	private static function dataSemu(){
		$c = [];
		$a = (self::$jdata > 1)? self::$jdata:1;
		$d = (self::$jkolom > 1)? self::$jkolom:1;
		for($b = 0; $b < $a; $b++){
			for($e = 0; $e < $d; $e++){
				$c['table']['col'.$b]['data'.$e] = 'data'.$e.'_col'.$b;
			}
		}
		return $c;
	}

	private static function notFound(){
		require 'pengaturan.php';
		if($_PENGATURAN['404_ON_ERROR'] === true){
			if(isset($_PENGATURAN['404_FILE']) && !empty($_PENGATURAN['404_FILE'])){
				if(is_file('error/'.$_PENGATURAN['404_FILE'])){
					require_once 'error/'.$_PENGATURAN['404_FILE'];
				}
				else{
					header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
					die();
				}
			}
			else{
				header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
				die();
			}
		}
		else{
			echo 'Halaman Tidak Ditemukan';
			die();
		}
	}

	private static function checkDir($path){
		$hasil =[];
		$a = explode(DIRECTORY_SEPARATOR, $path);
		foreach ($a as $b => $c) {
			if($b == sizeof($a)-1){
				$hasil['file'] = $c;
			}
			else{
				$hasil[] = $c;
			}
		}
		return $hasil;
	}

	private static function checkUrl(){
		$a = strlen(BASEDIR);
		$b = strpos($_SERVER['REQUEST_URI'], BASEDIR);
		if($b > -1){
			if($_SERVER['HTTP_HOST'] !== 'localhost'){
				$e = $b+$a;
			}
			else{
				$e = $b+$a+1;
			}
			$c = substr($_SERVER['REQUEST_URI'], $e, strlen($_SERVER['REQUEST_URI']));
			if(strlen($c) > 0 && explode('/', $c) > 0){
				return $c;
			}
			else{
				return null;
			}
		}
	}

}