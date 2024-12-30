<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
#[AllowDynamicProperties]
class Assets{
	
	function __construct(){
	}

	public function ambilAsset($dir, $file = ''){
		if(isset($_GET[0]) && strtolower($_GET[0]) === 'core'){
			die("HAYOoo.. mau ngapain? (-_- ')");
		}
		$drs = __DIR__.'/../assets/'.$dir;
		if(is_dir($drs)){
			$path = __DIR__."/../assets/$dir/$file";
			if($path !== '' && file_exists($path)){
				$nama = $this->filename($path);
				$this->ambil($path, $nama, '');
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

	private static function minic($path){
		if(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
			die("HAYOoo.. mau ngapain? (-_- ')");
		}
		else{
			try{
				$isi = file_get_contents($path);
				$isi1 = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $isi);
				$isi2 = str_replace(': ', ':', $isi1);
				$isi3 = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $isi2);
				$isi4 = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $isi3);
				file_put_contents($path, $isi4);
			}
			catch(Exception $e){
				header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
				die();
			}
		}
	}
	
	private function filename($path = ''){
		$ex = explode('/', $path);
		return $ex[sizeof($ex)-1];
	}

	private function ctype($filename) {
	    $ex = explode('.', $filename);
	    return static::mimetype(strtolower($ex[sizeof($ex)-1]));
	}

	public static function mimetype($type = ''){
		$mimet = array( 
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'tsx' => 'application/typescript',
			'ts' => 'application/typescript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',

        // images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			'webp' => 'image/webp',
			'raw' => 'image/x-dcraw',
    		'arw' => 'image/x-sony-arw',
    		'cr2' => 'image/x-canon-cr2',
    		'crw' => 'image/x-canon-crw',
    		'dcr' => 'image/x-kodak-dcr',
    		'dng' => 'image/x-adobe-dng',
    		'erf' => 'image/x-epson-erf',
    		'k25' => 'image/x-kodak-k25',
    		'kdc' => 'image/x-kodak-kdc',
    		'mrw' => 'image/x-minolta-mrw',
    		'nef' => 'image/x-nikon-nef',
    		'orf' => 'image/x-olympus-orf',
    		'pef' => 'image/x-pentax-pef',
    		'raf' => 'image/x-fuji-raf',
    		'sr2' => 'image/x-sony-sr2',
    		'srf' => 'image/x-sony-srf',
    		'x3f' => 'image/x-sigma-x3f',
    		'heic' => 'image/heic',


        // archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',

        // audio
			'mp3' => 'audio/mpeg',
			'cda' => 'application/x-cdf',

		// video
			'flv' => 'video/x-flv',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			'mp4' => 'video/mp4',
			'avi' => 'video/x-msvideo',
			'webm' => 'video/webm',
			'mkv' => 'video/x-matroska',
			'ts' => 'video/mp2t',
			'ogv' => 'video/ogg',
			'ogm' => 'video/ogg',
			'ogx' => 'video/ogg',
			'm4v' => 'video/mp4',
			'mpeg' => 'video/mpeg',
			'mpg' => 'audio/x-mpg',
			'wmv'=> 'video/x-ms-wmv',
			'rm' => 'video/vnd.rn-realmedia',
			'3gp' => 'video/3gpp',
			'3gp2'=> 'video/3gpp2',

        // adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',

        // ms office
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'docx' => 'application/msword',
			'xlsx' => 'application/vnd.ms-excel',
			'pptx' => 'application/vnd.ms-powerpoint',


        // open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

		// font
			'woff2' => 'font/woff2',
			'woff' => 'font/woff',
			'ttf' => 'font/ttf'
		);
		if(!empty($type) && array_key_exists($type, $mimet)){
			return $mimet[$type];
		}
		else{
			return 'text/plain';
		}
	}

	private function ambil($path, $file, $tipe){
		if(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
			die("HAYOoo.. mau ngapain? (-_- ')");
		}
		else{
			ob_start('ob_gzhandler');
			$fltype = $this->ctype($path);
	        header("Content-Type: ".$fltype);
	        header("Cache-Control: public, max-age=604800"); // needed for internet explorer
	        header("Content-Transfer-Encoding: gzip");
	        header("Content-Length:".filesize($path));
	        header("Content-Disposition: inline; filename=$file");
	        $files = fopen($path, "rb");
	        while (!feof($files)) {
			    $chunk = fread($files, 1024);
			    echo $chunk;
			    flush();
			    ob_flush();
			}
	        die();
		}
    }
}