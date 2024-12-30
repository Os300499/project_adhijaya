<?php
defined('PROTOCOLS') OR die("HAYOoo.. mau ngapain? (-_- ')");
/*
 * - ganti nilai SECURE_CROSS menjadi true ketika ingin mengamankan isi file
 * dari fungsi file_get_contents atau curl
 */

if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost'){
    // untuk offline / localhost

    /*
     * SECURE_CROSS mengamankan semua isi file dari fungsi file_get_contents
     * atau curl
     */
    $_PENGATURAN['SECURE_CROSS'] = false;
    
    /*
     * SECURE_SPESIFIK mengamankan isi folder tertentu dari fungsi 
     * file_get_contents atau curl
     * SECURE_SPESIFIK harus true untuk bisa mengamankan isi folder pada list
     * SECURE_FILES
     */
    $_PENGATURAN['SECURE_SPESIFIK'] = false;
    /*
     * SECURE_FILES berisi list permintaan yang akan diamankan ketika 
     * SECURE_SPESIFIK bernilai true.
     * jika isi array sama dengan '' atau "", maka akan dianggap keseluruhan
     */
    $_PENGATURAN['SECURE_REQ'] = array();
    
    /*
     * tulisan yang akan tampil ketika SECURE_CROSS atau SECURE_SPESIFIK
     * bernilai true
     */
    $_PENGATURAN['SECURE_PESAN'] = "HAYOoo.. mau ngapain? (-_- ')";
    
}
else{
    // untuk online

    /*
     * SECURE_CROSS mengamankan semua isi file dari fungsi file_get_contents
     * atau curl
     */
    $_PENGATURAN['SECURE_CROSS'] = false;
    /*
     * SECURE_SPESIFIK mengamankan isi folder tertentu dari fungsi 
     * file_get_contents atau curl
     * SECURE_SPESIFIK harus true untuk bisa mengamankan isi folder pada list
     * SECURE_FILES
     */
    $_PENGATURAN['SECURE_SPESIFIK'] = false;
    /*
     * SECURE_FILES berisi list permintaan yang akan diamankan ketika 
     * SECURE_SPESIFIK bernilai true
     * jika isi array sama dengan '' atau "", maka akan dianggap keseluruhan
     */
    $_PENGATURAN['SECURE_REQ'] = array();
    
    /*
     * tulisan yang akan tampil ketika SECURE_CROSS atau SECURE_SPESIFIK
     * bernilai true
     */
    $_PENGATURAN['SECURE_PESAN'] = "HAYOoo.. mau ngapain? (-_- ')";
}