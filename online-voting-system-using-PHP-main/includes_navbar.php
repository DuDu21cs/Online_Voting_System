<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Default language = English
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Change language if user clicks ?lang=am or ?lang=en
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en','am'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Load correct language file
$langFile = __DIR__ . '/../languages_' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    $lang = include($langFile);
} else {
    $lang = include(__DIR__ . '/../languages_en.php');
}

// Translation function
function __($key, $replace = []) {
    global $lang;
    $text = $lang[$key] ?? $key;
    foreach ($replace as $k => $v) {
        $text = str_replace(":$k", $v, $text);
    }
    return $text;
}
?>
