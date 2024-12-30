<?php
class Updating{
	private static $key = 'af4a6caaa6a48072e48dd66da8c3fb19f9517b2a';

	private static $waktu = 0;
	public static $database= '';

	function __construct(){
		self::$waktu = microtime(true);
		if(strpos($_SERVER['HTTP_HOST'], 'localhost') > -1 || !isset($_SERVER['HTTPS'])){
			if(is_file('.online_update') && file_exists('.online_update')){
				static::cekDatabase();
				static::cekFile();
			}
			else{
				static::buatFile();
			}
		}
	}

	private static function cekFile(){
		$kunci = '';
		$domain = '';
		$update = false;
		$content = [];
		if($buka = fopen('.online_update', 'r')){
			$berhenti = 1;
			for($mulai = 0; $mulai < 1; $mulai++){
				$baris = fgets($buka);
				if(strpos($baris, '#') > -1){
					// continue;
				}
				elseif(!empty($baris)){
					if(strpos($baris, '=') > -1){
						$status = ['ya', 'true', 'yes', 'y', 'a'];
						$var = ['upload', 'domain', 'kunci', 'database'];
						$ex = explode('=', $baris);
						if(sizeof($ex) == 2){
							if(in_array(trim($ex[0]), $var) && !empty($ex[1])){
								switch (trim($ex[0])) {
									case 'upload':
										if(in_array(trim($ex[1]), $status)){
											$update = true;
										}
										break;
									case 'domain':
										if(strpos(trim($ex[1]), 'https://') > -1){
											$domain = trim($ex[1]);
										}
										else{
											break 2;
										}
										break;
									case 'database':
										self::$database = trim($ex[1]);
										break;
									case 'kunci':
										$kunci = $ex[1];
										break;
								}
							}
							else{
								continue;
							}
						}
						else{
							continue;
						}
					}
					else{
						if(!empty(trim($baris))){
							$content[] = trim($baris);
						}
					}
				}
				
				if(feof($buka)){
					$berhenti = 0;
				}
				if($berhenti == 1){
					$mulai--;
				}
			}
			fclose($buka);
		}
		if(empty($kunci)){
			$kunci = hash_hmac('sha1', hash('sha256', microtime(true)), 'snd');
		}
		if($update === true && 
			!empty($kunci) && 
			!empty($domain) && 
			sizeof($content) > 0 && 
			strpos($_SERVER['HTTP_HOST'], 'localhost') > -1){
				$kirim = static::kirimUpdate($domain, $kunci, $content);
				if($kirim === true){
					static::resetUpload();
				}
				else{
					echo '<xmp> ini kirim ';var_dump($kirim);echo '</xmp>';
				}
		}
	}

	private static function extractLocal(){
		try{
		    if(class_exists('ZipArchive')){
		        $zip = new ZipArchive();
		    	$rootdir = realpath(__DIR__.'/../');
			    $file= realpath(__DIR__.'/../assets/doc/snd.zip');
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

	private static function cekDatabase(){
		if(file_exists('.online_update')){
			$hasil = [];
			$fl = file('.online_update');
			$isi = '';
			$tambah = false;
			$ada = false;
			foreach($fl AS $k => $v){
				if(strpos($v, '#') > -1){
					continue;
				}
				if(strpos($v, '=') > -1 && strpos($v, 'database') > -1){
					$ada = true;
					break;
				}
			}
			if(!$ada){
				for($ke = 0; $ke < sizeof($fl); $ke++){
					if($tambah){
						$isi .= "\n".'# kunci kedua database website'."\n"; 
						$isi .= '# kunci database bisa berupa apa saja kunci ini digunakan untuk.'."\n";
						$isi .= '# mengamankan database dari request diluar domain, kunci pertama yang'."\n";
						$isi .= '# digunakan adalah password dari database itu sendiri'."\n";
						$isi .= 'database ='."\n";
						$hasil[] = $isi;
						$ke--;
						$tambah = false;
					}
					elseif(strpos($fl[$ke], '=') > -1 && strpos($fl[$ke], 'upload') > -1){
						$hasil[] = $fl[$ke];
						$tambah = true;
					}
					else{
						$hasil[] = $fl[$ke];
					}
				}
				file_put_contents('.online_update', implode('', $hasil));
			}
		}
	}

    // untuk online_update upload
	private static function kirimUpdate($domain, $key, $content){
		if(class_exists('CurlFile')){
			if(static::compress($content) === true){
				$url = '';
				if($domain[strlen($domain)-1] == '/'){
					for ($ke = 0; $ke < strlen($domain)-1; $ke++) { 
						$url .= $domain[$ke];
					}
				}
				else{
					$url = $domain;
				}

				if(!empty($url)){
					$nmfl = realpath(__DIR__.'/../assets/doc/snd.zip');
					$kirim['isi'] = new CurlFile($nmfl, 'application/zip', 'isi');
					$kirim['key'] = $key;
					$ch = curl_init($url.'/online_update');
					curl_setopt($ch, CURLOPT_FAILONERROR, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
					$erno = curl_errno($ch);
					$erms = curl_error($ch);
					$isi = curl_exec($ch);
					curl_close($ch);
					unlink($nmfl);
					if($erno > 0){
				        return $erno;
					}
					elseif($isi == false){
					echo 'Gagal memperbaharui !';

					}
					else{
						return true;
					}
				}
			}
		}
		else{
			trigger_error("Tidak dapat mengirim online update. class CurlFile tidak ada");
		}
	}

	private static function isJson($string){
		$isi = json_decode($string);
		if(json_last_error() == JSON_ERROR_NONE){
			return $isi;
		}
		else{
			return false;
		}
	}

	private static function compress($content){
		$nmfl = __DIR__.'/../assets/doc/snd.zip';
		if(file_exists(realpath($nmfl))){
			unlink(realpath($nmfl));
		}
		if(is_string($content) && is_array(self::isJson($content))){
			$content = self::isJson($content);
		}
		$rootdir = realpath(__DIR__.'/../');
		$zip    = new ZipArchive();
        $zip->open($nmfl, ZipArchive::CREATE || ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootdir), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach($files as $name => $file){
            if(!$file->isDir()){
                $path = $file->getRealPath();
                if(is_array($content)){
	                for($ke = 0; $ke < sizeof($content); $ke++){
	                    if(strpos($path, $content[$ke]) >= -1){
			                $relativePath = substr($path, strlen($rootdir) + 1);
			                $zip->addFile($path, $relativePath);
			                break;
	                    }
	                }
                }
                elseif($content === 'semua'){
                	if(strpos($path, '.online_update') <= -1 && strpos($path, '.htaccess') <= -1){
			            $relativePath = substr($path, strlen($rootdir) + 1);
			            $zip->addFile($path, $relativePath);
	                }
                }
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

	private static function extract(){
		try{
		    if(class_exists('ZipArchive')){
		    	$rootdir = realpath(__DIR__.'/../');
			    $file= realpath(__DIR__.'/../assets/doc/snd.zip');
			    if(file_exists($file) && is_file($file)){
		        	$zip = new ZipArchive();
			    	$isi = file($file, FILE_SKIP_EMPTY_LINES);
			    	if(!empty($isi)){
				        $fl = $zip->open($file);
				        if($fl === TRUE){
				        	for($ke = 0; $ke < $zip->numFiles; $ke++){
				        		$flname = $zip->getNameIndex($ke);
				        		$ex = explode('\\', $flname);
				        		$dr = '';
				        		$flna = '';
				        		foreach($ex AS $qw => $rt){
				        			if($qw == sizeof($ex) - 1){
				        				$flna = $rt;
				        			}
				        			else{
				        				$dr .= $rt.'/';
				        				if(!empty($dr) && !is_dir($dr)){
				        					mkdir($dr);
				        				}
				        			}
				        		}
				        		copy("zip://$file#$flname", $dr.$flna);
				        	}
				            $zip->close();
				        }
			    	}
			    	unlink($file);
			    }
		    }
		}
		catch(Exception $e){

		}
	}

	public static function updates(){
		if(isset($_FILES['isi']['tmp_name']) && isset($_POST['key'])){
			if(static::cekKunci($_POST['key']) === true){
				static::jsonheader();
				$kirim['h'] = true;
				$file = file_get_contents($_FILES['isi']['tmp_name']);
				file_put_contents('assets/doc/snd.zip', $file);
				echo json_encode($kirim);
				static::extract();
			}
			else{
				header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
				die();
			}
		}
	}

	private static function cekKunci($kunci){
		if(file_exists('.online_update')){
			$isi = file('.online_update');
			$key = '';
			foreach($isi AS $ke => $v){
				if(strpos($v, '=') > -1 && strpos($v, 'kunci') > -1){
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

	private static function jsonheader(){
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	}

	private static function buatFile(){
		$isi  = '# file ini berisi nama file atau folder yang akan diupload ke hostingan online'."\n";
		$isi .= '# update dari local akan dilakukan ketika halaman web dari framework ini di refresh'."\n";
		$isi .= '# setiap 1 baris menandakan file atau folder yang akan diuplad. setelah'."\n";
		$isi .= '# file terupload, parameter \'upload\' akan terhapus '."\n";
		$isi .= '# status upload yang dapa digunakan adalah : \'ya\', \'true\', \'yes\', \'y\', \'a\''."\n\n";
		$isi .= 'upload ='."\n\n";
		$isi .= "\n".'# kunci kedua database website'."\n"; 
		$isi .= '# kunci database bisa berupa apa saja kunci ini digunakan untuk.'."\n";
		$isi .= '# mengamankan database dari request diluar domain, kunci pertama yang'."\n";
		$isi .= '# digunakan adalah password dari database itu sendiri'."\n";
		$isi .= 'database ='."\n";
		$isi .= '# url / link untuk upload file akan secara automatis di buat berdasarkan'."\n";
		$isi .= '# domain yang dimasukan dibawah. jika bukan domain dari file web ini dimasukan,'."\n";
		$isi .= '# file tidak akan terupload ke domain tersebut'."\n";
		$isi .= '# contoh : domain = https://domain.com'."\n\n";
		$isi .= 'domain = '."\n\n";
		$isi .= '# kunci dibawah digunakan untuk mengamankan website online dari'."\n";
		$isi .= '# postingan update yang tidak diinginkan kedalam website ini'."\n";
		$isi .= '# isi dari kunci bisa berupa apa saja. string, int, symbol'."\n\n";
		$isi .= 'kunci = '."\n\n";
		$isi .= '# dibawah ini berisi file atau folder yang akan di upload ke hostingan online'."\n";
		$isi .= '# jika yang diupload adalah folder, maka semua file dalam folder tersebut '."\n";
		$isi .= '# akan ikut terupload bersama folder tersebut'."\n";
		$isi .= '# contoh dibawah untuk folder :'."\n";
		$isi .= '# page'."\n\n";
		$isi .= '# contoh dibawah untuk file :'."\n";
		$isi .= '# sample.class.php'."\n";
		$isi .= '# template.view.php'."\n\n";
		$isi .= '# untuk upload file secara spesifik, gunakan tanda \\ untuk memisahkan folder'."\n";
		$isi .= '# contoh dibawah untuk file spesifik :'."\n\n";
		$isi .= '# templates\\main.view.php'."\n";
		$isi .= '# page\\dashboard\\main.view.php';
		file_put_contents('.online_update', $isi);
	}

	private static function resetUpload(){
		if(file_exists('.online_update')){
			$hasil = [];
			$isi = file('.online_update');
			foreach($isi AS $ke => $v){
				if(strpos($v, '=') > -1 && strpos($v, 'upload') > -1){
					$hasil[] = 'upload ='."\n";
				}
				else{
					$hasil[] = $v;
				}
			}
			file_put_contents('.online_update', implode('', $hasil));
		}
	}
}