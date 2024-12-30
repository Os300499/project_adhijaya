<?php 
defined('PROTOCOLS') OR die("HAYOoo.. mau ngapain? (-_- ')");
$httphost = (intval($_SERVER['HTTP_HOST']) > 0 || strpos($_SERVER['HTTP_HOST'], 'localhost') > -1)? 'localhost':$_SERVER['HTTP_HOST'];
if($httphost === 'localhost'){
	$_PENGATURAN['HOST_DB'] = 'localhost';
	$_PENGATURAN['USER_DB'] = 'root';
	$_PENGATURAN['PASS_DB'] = '';
	$_PENGATURAN['DATA_DB'] = 'project_akm';

	$dr = explode(DIRECTORY_SEPARATOR, __DIR__);

	$_PENGATURAN['ROOT_DIR'] = $dr[sizeof($dr) - 1];

	$_PENGATURAN['AUTO_UPDATE'] = false;
	if(intval($_SERVER['SERVER_PORT']) == 80){
		$httphost = 'localhost';
	}
	else{
		$httphost = $_SERVER['HTTP_HOST'];
	}
}
else{
	$_PENGATURAN['HOST_DB'] = 'localhost';
	$_PENGATURAN['USER_DB'] = 'alik9567_alik9567_Uproject_akm';
	$_PENGATURAN['PASS_DB'] = 'belalangbusu12@';
	$_PENGATURAN['DATA_DB'] = 'alik9567_project_akm';
	$_PENGATURAN['AUTO_UPDATE'] = false;
	
}
$_PENGATURAN['IMPORTING'] = false;
$_PENGATURAN['DIR_UMUM'] = 'umum';
$_PENGATURAN['404_ON_ERROR'] = true;
$_PENGATURAN['404_FILE'] = 'er_404.php';
$_PENGATURAN['ALLOWED_ASSETS'] = ['css', 'js', 'img', 'font', 'doc', 'QrData'];
$_PENGATURAN['MODE'] = 'development';
$_PENGATURAN['TABEL_CLASS'] = false;