<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
class customEncrypt
{
	private $key;
	private $key1;
	private $input;
	private $date;
	private $microtime;
	private $turn;
	private $enmethod;
	private $siv;
	private $iv;
	private $dateformat = 'd-m-Y H:i:s';
	private $symbol = array('!'=>'A', '@'=>'B', '#'=>'C', '$'=>'D', '%'=>'E', '^'=>'F', '&'=>'G', '*'=>'H', '('=>'I', ')'=>'J', '-'=>'K', '_'=>'L', '+'=>'M', '='=>'N', '{'=>'O', '}'=>'P', '['=>'Q', ']'=>'R', '|'=>'S', '\\'=>'T', '~'=>'U', '`'=>'V', ';'=>'W', ':'=>'X', '"'=>'Y', "'"=>'Z', '<'=>'AB', ','=>'AC', '>'=>'AD', '.'=>'AE', '?'=>'AF', '/'=>'AG', '\n'=>'AH', '\r'=>'AI', ' '=> '');
	
	private $unknowstring = array('\x20'=>' ', '\x21'=>'!', '\x22'=>'"', '\x23'=>'#', '\x24'=>'$', '\x25'=>'%', '\x26'=>'&', '\x27'=>"'", '\x28'=>'(', '\x29'=>')', '\x2A'=>'*', '\x2B'=>'+', '\x2C'=>',', '\x2D'=>'-', '\x2E'=>'.', '\x2F'=>'/', '\x30'=>'0', '\x31'=>'1', '\x32'=>'2', '\x33'=>'3', '\x34'=>'4', '\x35'=>'5', '\x36'=>'6', '\x37'=>'7', '\x38'=>'8', '\x39'=>'9', '\x3A'=>':', '\x3B'=>';', '\x3C'=>'<', '\x3D'=>'=', '\x3E'=>'>', '\x3F'=>'?', '\x40'=>'@', '\x41'=>'A', '\x42'=>'B', '\x43'=>'C', '\x44'=>'D', '\x45'=>'E', '\x46'=>'F', '\x47'=>'G', '\x48'=>'H', '\x49'=>'I', '\x4A'=>'J', '\x4B'=>'K', '\x4C'=>'L', '\x4D'=>'M', '\x4E'=>'N', '\x4F'=>'O', '\x50'=>'P', '\x51'=>'Q', '\x52'=>'R', '\x53'=>'S', '\x54'=>'T', '\x55'=>'U', '\x56'=>'V', '\x57'=>'W', '\x58'=>'X', '\x59'=>'Y', '\x5A'=>'Z', '\x5B'=>'[', '\x5C'=>'\\','\x5D'=>']', '\x5E'=>'^', '\x5F'=>'_', '\x60'=>'`', '\x61'=>'a', '\x62'=>'b', '\x63'=>'c', '\x64'=>'d', '\x65'=>'e', '\x66'=>'f', '\x67'=>'g', '\x68'=>'h', '\x69'=>'i', '\x6A'=>'j', '\x6B'=>'k', '\x6C'=>'l', '\x6D'=>'m', '\x6E'=>'n', '\x6F'=>'o', '\x70'=>'p', '\x71'=>'q', '\x72'=>'r', '\x73'=>'s', '\x74'=>'t', '\x75'=>'u', '\x76'=>'v', '\x77'=>'w', '\x78'=>'x', '\x79'=>'y', '\x7A'=>'z', '\x7B'=>'{', '\x7C'=>'|', '\x7D'=>'}', '\x7E'=>'~');
	
	private $diizinkan = array('string');
	private $urlsymbol = array('+'=>'%2B', '='=>'%3D', '/'=>'%2F');
	private $asciidecimal = array('');

	function __construct()
	{
		$this->key1 	= hash_hmac('md5', hash('md5', hash('sha256', 'alienscorp', true)), 'alienscorp');
		$this->enmethod = "AES-256-CBC";
		$this->date 	= microtime(true);
		$this->siv 		= $this->stripSymbol('SD*K?BSDv;n!dn:s{djvoh<2q>j01?1;,1');
		$this->iv 		= substr(hash('sha512', $this->siv), 9, 16);
		$this->input 	= hash('crc32b', microtime(true));
		// $this->key 		= openssl_encrypt($this->key1, $this->enmethod, $this->key, 0, $this->iv);
		$this->key 		= openssl_encrypt($this->key1, $this->enmethod, 'skjv', 0, $this->iv);
		$this->turn 	= (int) strlen(hash('md4', $this->key, true));
	}
	public function getApi($kosong = null){
		try {
			if($kosong != null){
				throw new Exception('getApi tidak boleh berisi nilai');
			}
			else{
				return $this->createApi();
			}
			// return $this->key;
		} catch (Exception $e) {
			$bt = debug_backtrace();
			$caller = array_shift($bt);
			$files = $caller['file'];
			$lines = $caller['line'];
			echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
		}
		
	}
	public function apaini($apa){
		return strtr($apa, array_flip($this->unknowstring));
	}
	public function ubah($apa){
		return strtr($apa, $this->unknowstring);
	}
	public function checkApi($api){
		try {
			if(!in_array(gettype($api), $this->diizinkan)){
				throw new Exception('API tidak boleh berbentuk '.gettype($api));
			}
			else{
				$api 	= $this->decrypting($api);
				return $api;
			}

		} catch (Exception $e) {
			$bt = debug_backtrace();
			$caller = array_shift($bt);
			$files = $caller['file'];
			$lines = $caller['line'];
			echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
		}
	}
	public function encrypt_string($string){
		try {
			if(!in_array(gettype($string), $this->diizinkan)){
				throw new Exception('encrypt_string tidak boleh berbentuk '.gettype($string));
			}
			else{
				$string = $this->encrypting($string);
				return $string;
			}
		} 
		catch (Exception $e) {
			$bt = debug_backtrace();
			$caller = array_shift($bt);
			$files = $caller['file'];
			$lines = $caller['line'];
			echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
		}
	}
	public function decrypt_string($string){
		$strings = $this->decrypting($string);
		return $strings;
	}
	private function createApi(){
		$create = $this->encrypting($this->input);
		return $create;
	}
	private function encrypting($string){
		$output = false;
		$mixdate  = '@'.$this->date;
		$output1  = openssl_encrypt($string, $this->enmethod, $this->key, 0, $this->iv);
		$mix 	  = $output1.$mixdate;
		$output2  = base64_encode($mix);
		if(openssl_encrypt($output2, $this->enmethod, $this->key, 0, $this->iv) !== FALSE){
			$output3  = openssl_encrypt($output2, $this->enmethod, $this->key, 0, $this->iv);
			$output = strtr($output3, $this->urlsymbol);
		}
		return $output;
	}
	private function decrypting($string){
		try {
			$output4 = strtr($string, array_flip($this->urlsymbol));
			$output1 = openssl_decrypt($output4, $this->enmethod, $this->key, 0, $this->iv);
			
			if($output1 !== FALSE){
				$output2 = base64_decode($output1);
				$getdate = substr($output2, strpos($output2, "@")+1, strlen($output2));
				// $validdate = $this->validateDate($getdate);
				// if($validdate){
					// $datenow = strtotime('now');
					// $datepass= strtotime($getdate);
					// if($datepass <= $datenow){
						$output3  = trim(substr($output2, 0, strpos($output2, "@")));
						if(openssl_decrypt($output3, $this->enmethod, $this->key, 0, $this->iv) !== FALSE){
						// 	// return true;
							$output5 = openssl_decrypt($output3, $this->enmethod, $this->key, 0, $this->iv);
							
							return $output5;
						}
						else{
							return false;
						}
					// }
					// else{
					// 	return false;
					// }
				// }
				// else{
				// 	return false;
				// }
			}
			else{
				return false;
			}
		} catch (Exception $e) {
			$bt = debug_backtrace();
			$caller = array_shift($bt);
			$files = $caller['file'];
			$lines = $caller['line'];
			echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
		}
	}
	private function stripSymbol($string){
		$result = htmlspecialchars(stripslashes(strtr(strip_tags(trim($string)), $this->symbol)));
		return $result;
	}

	private function validateDate($date)
	{	
		$d = DateTime::createFromFormat($this->dateformat, $date);
	    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($this->dateformat) === $date;
	}
	private function Error($msg)
	{
		throw new Exception($msg);
		die;
	}
}