<?php 
function isSimilar($str1, $str2, $threshold = 0.8) {
    $distance = levenshtein(strtolower($str1), strtolower($str2));
    $maxLength = max(strlen($str1), strlen($str2));
    $similarity = 1 - $distance / $maxLength;
    return $similarity >= $threshold;
}
function jsonheader($origin = true){
    $head = '*';
    if($origin){
        $head = BASEURL;
    }
    header("Access-Control-Allow-Origin: $head");
    header("Content-Type: application/json; charset=UTF-8");
}
if(!function_exists('dcd')){
    function dcd(){
        echo '<xmp>';
        array_map(function($apa){
            var_dump($apa);
        }, func_get_args());
        echo '</xmp>';
        die();
    }
}

if(!function_exists('dc')){
    function dc(){
        echo '<xmp>';
        array_map(function($apa){
            var_dump($apa);
        }, func_get_args());
        echo '</xmp>';
    }
}