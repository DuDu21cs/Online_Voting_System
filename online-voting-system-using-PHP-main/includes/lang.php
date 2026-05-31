<?php
// Start session if not already started
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Handle language selection via GET or default
if(isset($_GET['lang'])){
    $lang_code = $_GET['lang'];
    if(in_array($lang_code, ['en','am','om','ti','so','aa','hd','sid','wal','sg'])){
        $_SESSION['lang'] = $lang_code;
    }
}

// Default language
if(!isset($_SESSION['lang'])){
    $_SESSION['lang'] = 'en';
}

// Load translations dynamically
switch($_SESSION['lang']){
    case 'am': $lang = include 'languages_am.php'; break;
    case 'om': $lang = include 'languages_om.php'; break;
    case 'ti': $lang = include 'languages_ti.php'; break;
    case 'so': $lang = include 'languages_so.php'; break;
    case 'aa': $lang = include 'languages_aa.php'; break;
    case 'hd': $lang = include 'languages_hd.php'; break;
    case 'sid': $lang = include 'languages_sid.php'; break;
    case 'wal': $lang = include 'languages_wal.php'; break;
    case 'sg': $lang = include 'languages_sg.php'; break;
    default: $lang = include 'languages_en.php';
}

// Translation function
function __($key, $replace = []){
    global $lang;
    $text = isset($lang[$key]) ? $lang[$key] : $key;
    foreach($replace as $k => $v){
        $text = str_replace(':'.$k, $v, $text);
    }
    return $text;
}
?>
