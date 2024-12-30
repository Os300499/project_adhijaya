<?php
function getFileName($path, &$result = array()){
	$dir = opendir($path);
	while (($content = readdir($dir)) !== false) {
	    if ($content === '.' || $content === '..') {
	        continue;
	    }
	    if (is_dir("$path/$content") === false) {
	    	if(substr($content, -10) === '.class.php'){
	        	$result[] = realpath($path . DIRECTORY_SEPARATOR . $content);
	    	}
	    }
	    elseif(is_dir("$path/$content") === true && $content !== "core") {
	        getFileName("$path/$content", $result);
	    }
	}
	closedir($dir);
	return $result;
}
function getClass($classlist){
	$hasil = array();
	if(!empty($classlist)){
		if(is_array($classlist)){
			for($i = 0; $i < sizeof($classlist); $i++){
				require_once $classlist[$i];
				$nama = getClassName($classlist[$i]);
				$hasil[strtolower($nama)] = $nama;
			}
		}
		else{
			require_once $classlist;
			$nama = getClassName($classlist);
			$hasil = strtolower($nama);
		}
	}
	return $hasil;
}
function getClassName($file) {
	$fp = fopen($file, 'r');
	$class = $buffer = '';
	$i = 0;
	while (!$class) {
	    if (feof($fp)) break;
	    $buffer .= fread($fp, 140);
	    $tokens = @token_get_all($buffer);
	    if (strpos($buffer, '{') === false) continue;
	    for (;$i<count($tokens);$i++) {
	        if ($tokens[$i][0] === T_CLASS) {
	            for ($j=$i+1;$j<count($tokens);$j++) {
	                if ($tokens[$j] === '{') {
	                    $class = $tokens[$i+2][1];
	                }
	            }
	        }
	    }
	}
	fclose($fp);
	return $class;
}

function &cekClass($listClass=''){
	static $hasil = array();
	if($listClass !== '' && is_string($listClass) && isset($hasil[strtolower($listClass)])){
			return $hasil[strtolower($listClass)];
	}
	if(!empty($listClass) && is_array($listClass)){
		foreach ($listClass as $nama => $classes) {
			if(class_exists($classes, FALSE) && !isset($hasil[$classes])){
				if(!is_loaded($nama)){
					$class = new $classes();
					loaded_class($nama, $class);
					$hasil[strtolower($nama)] = $class;
				}
			}
		}
	}
	return $hasil;
}
function &loaded_class($name = '', $class = ''){
	static $_is_loaded = array();
	if ($class !== '' && $name !== '')
	{
		$_is_loaded[strtolower($name)] = $class;
	}
	return $_is_loaded;
}

function takeClass($name){
	if(is_loaded($name)){
		foreach (loaded_class() as $nama => $class) {
			if(strtolower($name) === $nama){
				return cekClass($name);
			}
		}
	}
	else{
		return cekClass(array(strtolower($name) => ucfirst($name)));
	}
	return null;
}
function is_loaded($name){
	foreach (loaded_class() as $nama => $class) {
		if(strtolower($name) === $nama){
			return true;
		}
	}
	return false;
}

// digunakan pada class oleread dan excelreader
function GetInt4d($data, $pos) {
    $value = ord($data[$pos]) | (ord($data[$pos+1]) << 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
    if ($value>=4294967294) {
        $value=-2;
    }
    return $value;
}

function checknew($url){
    $ch = curl_init($url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$er = curl_errno($ch);
	$isi = curl_error($ch);
	$out = curl_exec($ch);
	curl_close ($ch);
	if($er > 0){
	    return $isi;
	}
	elseif($er <= 0){
	    return $out;
	}
}

function cekUpdate(){
	if(isset($_GET[0]) && $_GET[0] !== 'assets'){
	    requestingUpdating();
	}
	elseif(!isset($_GET[0])){
		requestingUpdating();
	}
}

function requestingUpdating(){
	$out = null;
	$url = "https://frameworks.aliensgroup.my.id/api/framework/cekversi/";
	if(!str_contains($url, BASEURL)){
		$out = checknew($url);
		if(str_contains($out, '<!doctype html>')){
		   cekUpdate();
		}
		else{
			$hasil = json_decode($out);
			if(defined('FRAME_VERSION')){
				$new = '';
				if(is_array($hasil)){
					$new = $hasil['v'];
				}
				elseif(is_object($hasil)){
					$new = $hasil->v;
				}
				if(intval($new) !== intval(FRAME_VERSION)){
					downloadUpdate($new);
				}
	   		}
		}
	}
}

function extracts(){
	try{
	    if(class_exists('ZipArchive')){
	        $zip = new ZipArchive();
	    	$rootdir = realpath(__DIR__.'/../../../');
		    $file= realpath(__DIR__.'/../../doc/SND.zip');
		    if(file_exists($file) && is_file($file)){
		    	$isi = file($file, FILE_SKIP_EMPTY_LINES);
		    	if(!empty($isi)){
			        $fl = $zip->open($file);
			        if($fl === TRUE){
			            if($zip->extractTo($rootdir.DIRECTORY_SEPARATOR)){
			            	$zip->close();
			            }
			        }
		    	}
		    	unlink($file);
		    }
		    else{
		        downloadUpdate();
		    }
	    }
	    else{
	    	throw new Exception("Class ZipArchive pada php versi ".phpversion()." tidak ada");
	    }
	}
	catch(Exception $e){

	}
}
function downloads($url){
    $ch = curl_init($url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	$x = curl_errno($ch);
	$r = curl_error($ch);
	$isi = curl_exec($ch);
	curl_close($ch);
	if($x > 0){
        return $r;
	}
    if($x <= 0){
        return $isi;
    }
}  

function downloadUpdate($ver = 0){
    $filepath= realpath(__DIR__.'/../../doc/');
    $file = $filepath.DIRECTORY_SEPARATOR.'SND.zip';
    $url = "https://frameworks.aliensgroup.my.id/api/framework/updates/";
    if(!is_file($file) && !file_exists($file)){
        $isi = downloads($url);
	    if(str_contains($isi, '<!doctype html>')){
            downloadUpdate();
        }
        else{
            file_put_contents($file, $isi);
            extracts();
        }
    }
    else{
        unlink($file);
        downloadUpdate();
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }
}
spl_autoload_register(function($class){
	$coreFiles = getFileName(__DIR__.'/../../../core');
	rsort($coreFiles);
	$coreList 	= getClass($coreFiles);
	$coreClass 	=& cekClass($coreList);
});