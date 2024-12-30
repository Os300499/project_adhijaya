<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
class _AutoLoader{
	/**
	 *	method/fungsi yang akan dilakukan ketika public class digunakan
	 */
	private $defaultMethod = 'index';
	function __construct(){
		$this->resolve_url();
	}

	private function resolve_url(){
		include __DIR__.'/../pengaturan.php';
		try{
			$simulasi = false;
			if(class_exists('Simulasi')){
				$simulasi = Simulasi::$jalan;
			}
			if($simulasi === true){
				Simulasi::jalankan();
				die();
			}
			else{
				if(isset($_GET[0])){
					$dirini = realpath(__DIR__.'/../assets/php/classes');
					if(is_dir($dirini.DIRECTORY_SEPARATOR.$_GET[0])){
						if(isset($_PENGATURAN['DIR_UMUM']) && $_GET[0] == $_PENGATURAN['DIR_UMUM']){
							$this->notFound();
						}
						else{
							if(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
								die("HAYOoo.. mau ngapain? (-_- ')");
							}
							elseif(isset($_GET[1])){
								$this->loadClasses($dirini.DIRECTORY_SEPARATOR.$_GET[0]);
								$minta = takeClass(htmlspecialchars(stripslashes(strip_tags($_GET[1]))));
								$class = htmlspecialchars(stripslashes(strip_tags($_GET[1])));
								if(isset($_GET[3])){
									$param = htmlspecialchars(stripslashes(strip_tags($_GET[3])));
									$method = htmlspecialchars(stripslashes(strip_tags($_GET[2])));
									if($method !== $this->defaultMethod){
										$this->processRequest($minta, $method, $class, $param);
									}
									else{
										$this->notFound();
									}
								}
								elseif(isset($_GET[2])){
									$method = htmlspecialchars(stripslashes(strip_tags($_GET[2])));
									if($method !== $this->defaultMethod){
										$this->processRequest($minta, $method, $class);
									}
									else{
										$this->notFound();
									}
								}
								else{
									$method = $this->defaultMethod;
									$this->processRequest($minta, $method, $class);
								}
							}
							else{
								$this->notFound();
							}
						}
					}
					elseif($_GET[0] === 'assets'){
						if(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
							die("HAYOoo.. mau ngapain? (-_- ')");
						}
						$dirAsset = realpath(__DIR__.'/../assets');
						$minta = takeClass(htmlspecialchars(stripslashes(strip_tags($_GET[0]))));
						$method = htmlspecialchars(stripslashes(strip_tags($_GET[1])));
						if(!isset($_PENGATURAN['ALLOWED_ASSETS'])){
							$_PENGATURAN['ALLOWED_ASSETS'] = ['css', 'js', 'img', 'font', 'doc', 'QrData'];
						}
						if(in_array($method, $_PENGATURAN['ALLOWED_ASSETS'])){
							$fldr = array_search($method, $_PENGATURAN['ALLOWED_ASSETS']);
							if($fldr > -1){
								$mtd = $_PENGATURAN['ALLOWED_ASSETS'][$fldr];
								array_shift($_GET);
								array_shift($_GET);
								$fls = implode('/', $_GET);
								$minta->ambilAsset($mtd, $fls);
							}
							elseif($_PENGATURAN['404_ON_ERROR'] === true){
								if(isset($_PENGATURAN['404_FILE']) && !empty($_PENGATURAN['404_FILE'])){
									if(file_exists('error/'.$_PENGATURAN['404_FILE'])){
										require_once 'error/'.$_PENGATURAN['404_FILE'];
									}
									else{
										http_response_code(404);
										die();
									}
								}
								else{
									http_response_code(404);
									die();
								}
							}
							else{
								die("Nyasar ya? (>.<)");
							}
						}
						else{
							if($_PENGATURAN['404_ON_ERROR'] === true){
								if(isset($_PENGATURAN['404_FILE']) && !empty($_PENGATURAN['404_FILE'])){
									if(file_exists('error/'.$_PENGATURAN['404_FILE'])){
										require_once 'error/'.$_PENGATURAN['404_FILE'];
									}
									else{
										http_response_code(404);
										die();
									}
								}
								else{
									http_response_code(404);
									die();
								}
							}
							else{
								echo '$'.$_GET[0].'::'.$_GET[1].', Tidak ada';
								die();
							}
						}
					}
					elseif($_GET[0] === 'online_update'){
						if(class_exists('Updating')){
							if(is_callable(array('Updating', 'updates'))){
								Updating::updates();
							}
							else{
								if($_PENGATURAN['404_ON_ERROR'] === true && isset($_PENGATURAN['404_FILE']) && !empty($_PENGATURAN['404_FILE']) && file_exists('error/'.$_PENGATURAN['404_FILE'])){
										require_once 'error/'.$_PENGATURAN['404_FILE'];
								}
								else{
									http_response_code(404);
									die();
								}
							}
						}
					}
					else{
						if(isset($_PENGATURAN['DIR_UMUM'])){
							if(is_dir($dirini.DIRECTORY_SEPARATOR.$_PENGATURAN['DIR_UMUM'])){
								$loads = $this->loadClasses($dirini.DIRECTORY_SEPARATOR.$_PENGATURAN['DIR_UMUM']);
								$minta = takeClass(htmlspecialchars(stripslashes(strip_tags($_GET[0]))));
								$class = htmlspecialchars(stripslashes(strip_tags($_GET[0])));

								if(isset($_GET[3])){
									$method = htmlspecialchars(stripslashes(strip_tags($_GET[1])));
									$param = htmlspecialchars(stripslashes(strip_tags($_GET[3])));
									$this->processRequest($minta, $method, $class, $param, 'umum');
								}
								elseif(isset($_GET[1])){
									$method = htmlspecialchars(stripslashes(strip_tags($_GET[1])));
									$this->processRequest($minta, $method, $class, null, 'umum');
								}
								else{
									$method = $this->defaultMethod;
									$this->processRequest($minta, $method, $class, null, 'umum');
								}
							}
							else{
								$this->notFound('umum');
							}
						}
						else{
							if(isset($_PENGATURAN['404_FILE']) && !empty($_PENGATURAN['404_FILE'])){
								if(file_exists(__DIR__.'/../error/'.$_PENGATURAN['404_FILE'])){
									require_once __DIR__.'/../error/'.$_PENGATURAN['404_FILE'];
								}
								else{
									http_response_code(404);
									die();
								}
							}
							else{
								http_response_code(404);
								die();
							}
						}
					}
				}
				elseif(!isset($_GET[0])){
					$this->halaman_awal();
				}
				else{
					die("Nyasar ya? (>.<)");
				}
			}
		}
		catch(Exception $e){
		}
	}

	private function halaman_awal(){
		try {
			if(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
				die("HAYOoo.. mau ngapain? (-_- ')");
			}
			else{
				$method = $this->defaultMethod;

				$pubList  = getClass([realpath(__DIR__.'/../assets/php/classes/awal.class.php')]);
				$pubclass =& cekClass($pubList);
				$minta = takeClass('awal');
				$minta->$method();
			}
		} 
		catch (Exception $e) {
		}
	}

	private function processRequest($minta, $method, $class, $param = '', $untuk = 'class'){
		$instance = null;
		if(gettype($minta) === 'object'){
			$instance = $minta;
		}
		elseif(array_key_exists(strtolower($class), $minta)){
			$instance = $minta[strtolower($class)];
		}
		if(!empty($instance)){
			if(is_object($instance)){
				if(is_callable(array($instance, $method))){
					if(!empty($param)){
						$instance->$method($param);
					}
					else{
						$instance->$method();
					}
				}
				else{
					$this->notFound($untuk);
				}
			}
			else{
				if(is_callable(array($instance, $method))){
					if(!empty($param)){
						$instance[strtolower($class)]->$method($param);
					}
					else{
						$instance[strtolower($class)]->$method();
					}
				}
				else{
					$this->notFound($untuk);
				}
			}
		}
		else{
			$this->notFound($untuk);
		}
	}

	private function notFound($untuk = 'class'){
		include __DIR__.'/../pengaturan.php';
		if($_PENGATURAN['404_ON_ERROR'] === true){
			if(isset($_PENGATURAN['404_FILE']) && !empty($_PENGATURAN['404_FILE'])){
				if(file_exists(__DIR__.'/../error/'.$_PENGATURAN['404_FILE'])){
					require_once __DIR__.'/../error/'.$_PENGATURAN['404_FILE'];
				}
				else{
					http_response_code(404);
					die();
				}
			}
			else{
				http_response_code(404);
				die();
			}
		}
		else{
			if($untuk == 'api'){
				if(isset($_GET[2])){
					echo '$'.$_GET[0].'->'.$_GET[1].'($param), Tidak Ada';
				}
				elseif(isset($_GET[1])){
					echo '$'.$_GET[0].'->'.$_GET[1].'(), Tidak Ada';
				}
				else{
					echo '$'.$_GET[0].'->index(), Tidak Ada Pada Dir Api';
				}
			}
			elseif($untuk == 'umum'){
				if(isset($_GET[3])){
					echo '$'.$_GET[0].'->'.$_GET[1].'($param), Tidak Ada';
				}
				elseif(isset($_GET[1])){
					echo '$'.$_GET[0].'->'.$_GET[1].'(), Tidak Ada';
				}
				else{
					echo '$'.$_GET[0].'->index(), Tidak Ada Pada Dir Umum';
				}
			}
			else{
				if(isset($_GET[3])){
					echo '$'.$_GET[1].'->'.$_GET[2].'($param), Tidak Ada';
				}
				elseif(isset($_GET[2])){
					echo '$'.$_GET[1].'->'.$_GET[2].'(), Tidak Ada';
				}
				elseif(isset($_GET[1])){
					echo '$'.$_GET[1].'->index(), Tidak Ada';
				}
				else{
					echo 'Dir '.$_GET[0].' Tidak Ada Pada Classes';
				}
			}
			die();
		}
	}

	private function loadClasses($path){
		$pubFiles = getFileName($path);
		$pubList  = getClass($pubFiles);
	}

}