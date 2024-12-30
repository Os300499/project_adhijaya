<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
#[AllowDynamicProperties]
class DataBase{
	private static $hostdb, $userdb, $passdb, $datadb, $tmpDir,
	$listTable=array(), $con = array(), $jumlahKoneksi = 0, $singleMysql = array(),
	$singlePdo = array(), $multiMysql = array(), $multiPdo = array(), $pengaturan = null, $httpHost = 'localhost', $mode = 'd';
	public $pdo = '', $mysqli  = '';
	function __construct(){
		usleep(1);
		require __DIR__.'/../pengaturan.php';
		self::$tmpDir   = __DIR__.'/../asets/tmp';
		if(isset($_PENGATURAN)){
			self::$pengaturan = $_PENGATURAN;
		}
		if(isset($httphost)){
			self::$httpHost = $httphost;
		}
		if(isset(self::$pengaturan['MODE'])){
			self::$mode = self::$pengaturan['MODE'];
		}
		if(!empty(self::$pengaturan)){
			if(isset($_GET[0]) && $_GET[0] === 'datadase'){
				if(isset(self::$pengaturan['DB'])){
				    if(gettype(self::$pengaturan['DB']) === 'array' || gettype(self::$pengaturan['DB']) === 'object'){
				        foreach(self::$pengaturan['DB'] as $idx => $vl){
				            if(gettype($vl) === 'array' || gettype($vl) === 'object'){
				                foreach($vl as $nama => $nilai){
				                    $$nama = $nilai;
				                }
				                $setting['con']['name'] = $DATA_DB;
				                $setting['con']['con'] = array($HOST_DB, $USER_DB, $DATA_DB, $PASS_DB);
				                array_push(self::$con, $setting);
				            }
				        }
				    }
				}
				else{
		    		self::$hostdb = self::$pengaturan['HOST_DB'];
		    		self::$userdb = self::$pengaturan['USER_DB'];
		    		self::$passdb = self::$pengaturan['PASS_DB'];
		    		self::$datadb = self::$pengaturan['DATA_DB'];
		    		$this->pdo = static::pdo();
		    		$this->mysqli = static::mysqli();
				}
			}
	        if(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
	            die("HAYOoo.. mau ngapain? (-_- ')");
	        }
			else{
				if(isset(self::$pengaturan['DB'])){
				    if(gettype(self::$pengaturan['DB']) === 'array' || gettype(self::$pengaturan['DB']) === 'object'){
				        foreach(self::$pengaturan['DB'] as $idx => $vl){
				            if(gettype($vl) === 'array' || gettype($vl) === 'object'){
				                foreach($vl as $nama => $nilai){
				                    $$nama = $nilai;
				                }
				                $setting['con']['name'] = $idx;
				                $setting['con']['con'] = array($HOST_DB, $USER_DB, $DATA_DB, $PASS_DB);
				                array_push(self::$con, $setting);
				                self::$jumlahKoneksi += 1;
				            }
				        }
				    }
				}
				else{
		    		self::$hostdb = self::$pengaturan['HOST_DB'];
		    		self::$userdb = self::$pengaturan['USER_DB'];
		    		self::$passdb = self::$pengaturan['PASS_DB'];
		    		self::$datadb = self::$pengaturan['DATA_DB'];
		    		$this->pdo = static::pdo();
		    		$this->mysqli = static::mysqli();
		    		self::$jumlahKoneksi = 1;
				}
			}
			if(self::$httpHost != 'localhost' && self::$pengaturan['IMPORTING'] === true){
				self::import(true);
			}
			if(isset($_PENGATURAN['TABEL_CLASS']) && $_PENGATURAN['TABEL_CLASS'] === true)
				self::buatClass();
		}
	}

	public static function conSize(){
		return self::$jumlahKoneksi;
	}

	public static function getMysqli(int $ke){
		if(isset(self::$multiMysql[$ke][0])){
			return self::$multiMysql[$ke][0];
		}
		elseif(sizeof(self::$con) > 0){
			$con = self::$con[$ke]['con']['con'];
			$mysqli['con']['name'] = $ke;
			$mysqli['con']['con'] = self::mysqli1($con[0], $con[1], $con[2], $con[3]);
			self::$multiMysql[$ke][0] = $mysqli;
			return $mysqli;
		}
		elseif(!empty(self::$mysqli)){
			die('Warning : gunakan $this->database->mysqli');
		}
		else{
			die("Error : Tidak ada koneksi ke database !");
		}

	}

	public static function getPdo(int $ke){
		if(sizeof(self::$con) > 0){
			$con = self::$con[$ke];
			return self::pdo1($con[0], $con[1], $con[2], $con[3]);
		}
		else{
			die("Error : Tidak ada koneksi ke database !");
		}
	}

	private static function mysqli(){
		$mysqli = null;
		if(!empty(self::$singleMysql)){
			$mysqli = array_pop(self::$singleMysql);
		}
		elseif(empty(self::$mysqli)){
			$mysqli = [];
		    if(!empty(self::$hostdb) && !empty(self::$userdb) && !empty(self::$passdb) && !empty(self::$datadb)){
		    	$mysqli['con']['name'] = '0';
			    $mysqli['con']['con'] = new mysqli(self::$hostdb, self::$userdb, self::$passdb, self::$datadb);
		    }
			elseif(!empty(self::$hostdb) && !empty(self::$userdb) && !empty(self::$datadb)){
				$mysqli['con']['name'] = '0';
				$mysqli['con']['con'] = new mysqli(self::$hostdb, self::$userdb, self::$passdb, self::$datadb);
			}
			array_push(self::$singleMysql, $mysqli);
		}
		else{
			$mysqli = self::$mysqli;
			array_push(self::$singleMysql, $mysqli);
		}
		return $mysqli;
	}

	private static function pdo(){
	    if(!empty(self::$hostdb) && !empty(self::$userdb) && !empty(self::$passdb) && !empty(self::$datadb)){
		    return new PDO('mysql:host='.self::$hostdb.';dbname='.self::$datadb, self::$userdb, self::$passdb);
	    }
	    elseif(!empty(self::$hostdb) && !empty(self::$userdb) && !empty(self::$datadb)){
			return new PDO('mysql:host='.self::$hostdb.';dbname='.self::$datadb, self::$userdb, self::$passdb);
		}
	}
	
	private static function mysqli1($host, $user, $db, $pass = ''){
	    return new mysqli($host, $user, $pass, $db);
	}
    
    private static function pdo1($host, $user, $db, $pass = ''){
        return new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    }

    private static function buatBackup($mysqli){
    	if(!empty($mysqli)){
    		$dir = __DIR__.'/../database';
    		if(!is_dir($dir)){
    			mkdir($dir);
    		}
    		if(isset($mysqli[0])){
    			for($ke = 0; $ke < sizeof($mysqli); $ke++){
    				$con = $mysqli[$ke]['con']['con'];
    				self::simpanTable($con);
    				$drname = $dir.'/'.$ke;
    				if(!is_dir($drname)){
    					mkdir($drname);
    				}
    				foreach(self::$listTable AS $no => $tbl){
    					$hasil = '';
    					$filenm = $drname.'/'.$tbl.'.sql';
    					file_put_contents($filenm, $hasil);
    					$hasil .= self::sqlHeader();
    					$hasil .= self::structureTable($tbl, $con);
    					$hasil .= self::dumpTable($tbl, $con);
    					file_put_contents($filenm, $hasil);
    				}
    			}
    		}
    		else{
    			self::simpanTable($mysqli['con']['con']);
    			$drname = $dir.'/0';
    			if(!is_dir($drname)){
    				mkdir($drname);
    			}
    			foreach(self::$listTable AS $no => $tbl){
    				$hasil = '';
    				$filenm = $drname.'/'.$tbl.'.sql';
    				file_put_contents($filenm, $hasil);
    				$hasil .= self::sqlHeader();
    				$hasil .= self::structureTable($tbl, $mysqli['con']['con']);
    				$hasil .= self::dumpTable($tbl, $mysqli['con']['con']);
    				file_put_contents($filenm, $hasil);
    			}
    		}
    		return true;
    	}
    	else{
    		trigger_error("Gagal Backup Database. Tidak ada koneksi ke database", E_USER_ERROR);
    		return false;
    	}
    }

    public static function import(bool $hapus = false){
    	try {
	    	$dir = realpath(__DIR__.'/../database');
	    	if(file_exists($dir) && is_dir($dir)){
		    	$hasil = array();
		    	$ke = 0;
		    	self::openFolder($dir, $hasil, $ke);
		    	if(self::$jumlahKoneksi > 1){
			    	for($conke = 0; $conke < self::$jumlahKoneksi; $conke++){
			    		if(isset($hasil[$conke]) && is_array($hasil[$conke])){
			    			$con = self::getMysqli($conke);
			    			$mysqli = $con['con'];
			    			$isi = '';
			    			foreach($hasil[$conke] AS $fl){
			    				$isi = file_get_contents($fl);
			    				if ($mysqli->multi_query($isi)) {
			    					do {
			    						if ($result = $mysqli->store_result()) {
			    							$result->free();
			    						}
			    					} while ($mysqli->more_results() && $mysqli->next_result());
			    				}
			    				else{
			    					throw new Exception('Mysqli Import Error : '.$mysqli->error);
			    				}
			    			}
			    			$mysqli->close();
			    			echo 'import ke '.$conke.' oke</br>';
			    		}
			    		else{
			    			echo 'backup database ke '.$conke.' tidak ada</br>';
			    		}
			    	}
			    	if($hapus){
			    		self::cek($dir);
			    		rmdir($dir);
			    	}
		    	}
		    	elseif(self::$jumlahKoneksi == 1){
		    		$con = self::mysqli();
		    		$mysqli = $con['con']['con'];
		    		foreach($hasil[0] AS $fls){
			    		$isi = file_get_contents($fls);
				    	if ($mysqli->multi_query($isi)) {
					        do {
					            if ($result = $mysqli->store_result()) {
					                $result->free();
					            }
					        } while ($mysqli->more_results() && $mysqli->next_result());
				    	}
				    	else{
				    		throw new Exception('Mysqli Import Error : '.$mysqli->error);
				    	}
			    	}
			    	$mysqli->close();
			    	echo 'import oke';
			    	if($hapus){
			    		self::cek($dir);
			    		rmdir($dir);
			    	}
		    	}
		    	else{
		    		throw new Exception('Error : Tidak ada koneksi ke database !');
		    	}
	    	}
	    	else{
	    		trigger_error('Error : folder database tidak ada untuk di import !', E_USER_ERROR);
	    	}
    	} 
    	catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

    private static function openFolder($pt = '', &$hasil = array(), &$ke = 0){
		$dr = opendir($pt);
		while(($content = readdir($dr)) !== false){
			if ($content === '.' || $content === '..') {
		        continue;
		    }
		    if(is_dir("$pt/$content") === false && substr("$pt/$content", -4) === '.sql'){
		    	array_push($hasil[$ke-1], "$pt/$content");
		    }
		    elseif(is_dir("$pt/$content") === true) {
		    	$hasil[$ke] = array();
		    	$ke += 1;
	        	self::openFolder("$pt/$content", $hasil, $ke);
	    	}
		}
		closedir($dr);
		return $hasil;
    }

    public function backup(){
    	if(isset($_GET[2]) && strtolower($_GET[2]) === 'h'){
    		if(self::checkings() === true){
    			self::checkingRequest();
    		}
    		else{
    			die("HAYOoo.. mau ngapain di database? (-_- ')");
    		}
		}
		else{
			$mysqli = null;
	    	if(sizeof(self::$con) > 0){
	    		foreach(self::$con AS $ke => $isi){
	    			$mysqli[] = self::getMysqli($ke);
	    		}
	    	}
	    	else{
	    		$mysqli = $this->mysqli;
	    	}
	    	return self::buatBackup($mysqli);
		}
    }

    private static function simpanTable($mysqli){
        if($mysqli != null){
        	$con = null;
        	if($mysqli instanceof mysqli){
        		$con = $mysqli;
        	}
        	else{
        		$con = $mysqli['con'];
        	}
            $ambil = $con->query("SHOW TABLES");
            if(!empty(self::$listTable)){
            	self::$listTable = array();
            }
            while($hasil = $ambil->fetch_array()){
                self::$listTable[] = $hasil[0];
            }
        }
    }

    private static function structureTable($table, $mysqli){
    	if(!empty($mysqli)){
    		$cek = $mysqli->query("SHOW CREATE TABLE $table");
    		$hasil = $cek->fetch_row();
    		$isi = strtr($hasil[1], ['CREATE TABLE '=>'CREATE TABLE IF NOT EXISTS ']);
    		$kirim = "\n-- ---------------------------------------------------------\n--\n-- Struktur Untuk Table : `$table`\n--\n-- ---------------------------------------------------------\n".$isi.";\n";
    		return $kirim;
    	}
    	else{
    		trigger_error("Gagal Backup Database. Tidak ada koneksi ke database", E_USER_ERROR);
    	}
    }

    private static function dumpTable($table, $mysqli){
    	if(!empty($mysqli)){
    		$kirim = '';
    		$cek = $mysqli->query("SELECT * FROM $table");
    		$jumlah = $cek->field_count;
    		for($ke = 0; $ke < $jumlah; $ke++){
    			$skali = 1;
    			while($isi = $cek->fetch_row()){
    				if($skali == 1){
    					$kirim .= "\n--\n-- Isi Data Dari Table : `{$table}`\n--\n";
    					$isikolom = array();
    					while($kolom = $cek->fetch_field()){
    						$isikolom[] = '`'.$kolom->name.'`';
    					}
    					$koloms = implode(', ', $isikolom);
    					$kirim .= "INSERT IGNORE INTO `$table`({$koloms}) VALUES\n(";
    					$skali = 0;
    				}
    				else{
    					$kirim .= '(';
    				}
    				for($ke1 = 0; $ke1 < $jumlah; $ke1++){
    					if(!empty($isi[$ke1])){
	    					$isi[$ke1] = str_replace('\'','\'\'', preg_replace("/\n/","\\n", $isi[$ke1]));
	    					if(isset($isi[$ke1])){
	    						$kirim .= is_numeric($isi[$ke1]) ? $isi[$ke1]: '\''.$isi[$ke1].'\'' ; 
	    					}
	    					else{
	    						$kirim .= '\'\'';
	    					}
    					}
    					else{
    						$kirim .= '\'\'';
    					}
    					if($ke1 < ($jumlah - 1)){
    						$kirim .= ', ';
    					}
    				}
    				$kirim .= "),\n";
    			}
				preg_match("/\),\n/", $kirim, $match, false, -3);
				if(isset($match[0])){
					$kirim = substr_replace( $kirim, ";", -2);
				}
			}
			return $kirim;
    	}
    	else{
    		trigger_error("Gagal Backup Database. Tidak ada koneksi ke database", E_USER_ERROR);
    	}
    }

    private static function sqlHeader(){
    	$kirim = "-- ---------------------------------------------------------\n-- Aliensgroup Database Backup\n-- https://aliensgroup.my.id/\n-- ---------------------------------------------------------\nSET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\nSTART TRANSACTION;\nSET time_zone = \"+00:00\";\n";
    	return $kirim;
    }

    private function checkingRequest(){
    	if(isset($_POST['key']) && self::cekKunci($_POST['key']) === true){
    		include __DIR__.'/../pengaturan.php';
    		$mysqli = null;
    		if(sizeof(self::$con) > 0){
    			foreach(self::$con AS $ke => $isi){
    				$mysqli[] = self::getMysqli($ke);
    			}
    		}
    		else{
    			$mysqli = $this->mysqli;
    		}
    		if(self::buatBackup($mysqli) === true && self::compressing() == true){
    			self::zipHeader(__DIR__.'/../db.zip');
    		}
    		else{
    			die("Gagal compress database");
    		}
    	}
    	else{
    		die("HAYOoo.. mau ngapain di database? (-_- ')");
    	}
    }

    private static function cek($pt = ''){
    	$rs = array();
		$dr = opendir($pt);
		while(($content = readdir($dr)) !== false){
			if ($content === '.' || $content === '..') {
		        continue;
		    }
		    if(is_dir("$pt/$content") === false && "$pt/$content" != __FILE__){
		    	unlink("$pt/$content");
		    }
		    elseif(is_dir("$pt/$content") === true) {
		    	array_push($rs, "$pt/$content");
	        	self::cek("$pt/$content");
	    	}
		}
		closedir($dr);
		self::cek1($rs);
	}

	private static function cek1(array $dr){
		if(!empty($dr)){
			foreach ($dr as $a => $b) {
				rmdir($b);
			}
		}
	}

	private static function checkings(){
		$m = str_split('hayoomaungapain');
		for($ke = 0; $ke <sizeof($m); $ke++){
			if(isset($_GET[(2+$ke)])){
				if($_GET[(2+$ke)] !== $m[$ke]){
					return false;
					break;
				}
			}
			else{
				return false;
				break;
			}
		}
		return true;
	}

    private static function compressing(){
    	$nmfl = __DIR__.'/../db.zip';
    	if(file_exists(realpath($nmfl))){
    		unlink($nmfl);
    	}
    	$rootdir = realpath(__DIR__.'/../database');
    	$zip    = new ZipArchive();
    	$zip->open($nmfl, ZipArchive::CREATE || ZipArchive::OVERWRITE);
    	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootdir), RecursiveIteratorIterator::LEAVES_ONLY);
    	foreach($files as $name => $file){
    		if(!$file->isDir()){
    			$path = $file->getRealPath();
    			$relativePath = substr($path, strlen($rootdir) + 1);
    			$zip->addFile($path, $relativePath);
    		}
    	}
    	$zip->close();
    	if(file_exists($nmfl)){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    private static function cekKunci($kunci){
		if(file_exists('.online_update')){
			$isi = file('.online_update');
			$key = '';
			foreach($isi AS $ke => $v){
				if(strpos($v, '=') > -1 && strpos($v, 'database') > -1){
					$ex = explode('=', $v);
					$key = hash_hmac('sha1', hash('sha256', $ex[1]), 'snd');
					break;
				}
			}
			$k = hash_hmac('sha1', hash('sha256', $kunci), 'snd');
			if($key === $k){
				return true;
			}
		}
		return false;
	}

	private static function zipHeader($path){
		header($_SERVER['SERVER_PROTOCOL'].' 200 Oke');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Max-Age: 3600");
        header("Content-Type: application/zip");
        header("Content-Length:".filesize($path));
        header("Content-Transfer-Encoding: Binary");
        header('Content-Disposition: attachment; filename=hadiah_db.zip');
        $files = fopen($path, "rb");
        while (!feof($files)) {
            $chunk = fread($files, 512);
            echo $chunk;
            flush();
        }
		unlink($path);
		self::cek(realpath(__DIR__.'/../database'));
		rmdir(realpath(__DIR__.'/../database'));
	    die();
    }

    private static function buatClass(){
    	$mysqli = null;
	    if(sizeof(self::$con) > 0){
	    	foreach(self::$con AS $ke => $isi){
	    		$con = self::getMysqli($ke);
	    		$mysqli[] = $con['con']['con'];
	    	}
	    }
	    else{
	    	$mysqli = self::mysqli();
	    }
	    self::checkFile($mysqli);
    }

    private static function checkFile($mysqli){
    	try{
	    	if(!empty($mysqli)){
	    		$dir = __DIR__.'/../table';
	    		if(!is_dir($dir)){
	    			mkdir($dir);
	    		}
	    		if(isset($mysqli['con'])){
	    			$mysqli = $mysqli['con']['con'];
	    		}
	    		if(!($mysqli instanceof mysqli) AND isset($mysqli[0])){
	    			for($ke = 0; $ke < sizeof($mysqli); $ke++){
	    				$con = $mysqli[$ke];
	    				self::simpanTable($con);
	    				$drname = $dir.'/'.$ke;
	    				if(!is_dir($drname)){
	    					mkdir($drname);
	    				}
	    				foreach(self::$listTable AS $no => $tbl){
	    					$filenm = $drname.'/.table_'.$tbl;
	    					if(!file_exists($filenm)){
	    						$hasil = '';
			    				file_put_contents($filenm, $hasil);
			    				$hasil .= self::classHeader($tbl);
			    				$hasil .= self::classContent();
			    				$hasil .= '}';
		    					file_put_contents($filenm, $hasil);
	    					}
	    				}
	    			}
	    		}
	    		else{
	    			self::simpanTable($mysqli);
	    			$drname = $dir.'/0';
	    			if(!is_dir($drname)){
	    				mkdir($drname);
	    			}
	    			if(empty($map)){
	    				$map[0] = array();
	    			}
	    			foreach(self::$listTable AS $no => $tbl){
	    				$filenm = $drname.'/.table_'.$tbl;
	    				if(!file_exists($filenm)){
		    				$hasil = '';
			    			file_put_contents($filenm, $hasil);
			    			$hasil .= self::classHeader($tbl);
			    			$hasil .= self::classContent();
			    			$hasil .= '}'; 
		    				file_put_contents($filenm, $hasil);
	    				}
	    			}
	    		}
	    	}
    	}
    	catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

    private static function classHeader($table){
    	return "<?php\ndefined('BASEURL') OR die(\"HAYOoo.. mau ngapain? (-_- ')\");\n#[AllowDynamicProperties]\nclass table_$table extends Mysql{\n";
    }

    private static function classContent(){
    	$isi = "\n\tpublic function simpan(\$data, \$table=null, \$mysqli=null){\n\t\treturn parent::simpan(\$data);\n\t}\n\n\tpublic function hapus(\$id, \$table = null, \$mysqli = null){\n\t\treturn parent::hapus(\$id);\n\t}\n\n\tpublic function ubah(\$id, \$set, \$table = null, \$mysqli = null){\n\t\treturn parent::ubah(\$id, \$set);\n\t}\n\n\tpublic function cekId(\$id, \$table = null, \$mysqli = null){\n\t\treturn parent::cekId(\$id);\n\t}\n\n\tpublic function ambilSemua(\$table = null, \$mysqli = null){\n\t\treturn parent::ambilSemua();\n\t}\n\n\tpublic function ambilSemuaSort(\$kolom = null, \$ord = 'ASC', \$table = null, \$mysqli = null){\n\t\treturn parent::ambilSemuaSort(\$kolom, \$ord);\n\t}\n\n\tpublic function ambil(\$pilihan, \$table = null, \$mysqli = null){\n\t\treturn parent::ambil(\$pilihan);\n\t}\n\n\tpublic function ambilOrder(\$pilihan, \$kolom, \$order = true, \$table = null, \$mysqli = null){\n\t\treturn parent::ambilOrder(\$pilihan, \$kolom, \$order);\n\t}\n\n\tpublic function ambilKondisi(\$where = null, \$whereval = null, \$logic = null, \$table = null, \$mysqli = null){\n\t\treturn parent::ambilKondisi(\$where, \$whereval, \$logic);\n\t}\n\n\tpublic function ambilKondisiOrder(\$where = null, \$whereval = null, \$logic = null, \$kolom = null, \$order=true, \$table = null, \$mysqli = null){\n\t\treturn parent::ambilKondisiOrder(\$where, \$whereval, \$logic, \$kolom, \$order);\n\t}\n\n\tpublic function ambilBeberapa(\$pilihan, \$where = null, \$whereval = null, \$logic = null, \$table = null, \$mysqli = null){\n\t\treturn parent::ambilBeberapa(\$pilihan, \$where, \$whereval, \$logic);\n\t}\n\n\tpublic function ambilSatuId(\$id, \$table = null, \$type=null, \$mysqli = null){\n\t\treturn parent::ambilSatuId(\$id);\n\t}\n\n\tpublic function query(\$text = null, \$mysqli = null){\n\t\treturn parent::query(\$text);\n\t}\n\n\tpublic function prepare(\$text = null, \$mysqli = null){\n\t\treturn parent::prepare(\$text);\n\t}\n\n";
    	return $isi;
    }
}
