<?php
declare(strict_types=1);

header('Access-Control-Allow-Origin: *');

require_once 'processE2u.php';
require_once 'processGlosbe.php';

// echo '<pre>';
// print_r($_SERVER);
// print_r($_GET);

try {
    $target = explode('/', $_SERVER['REQUEST_URI'])[2] ?? '';
    // echo $target, '<br>';
    $dic = explode('?', $target)[0];
    // echo $dic;
    $word = $_GET['word'] ?? '';

    if($dic === 'e2u') {
        $word = str_replace(' ', '+', $word);
        // $url = 'https://e2u.org.ua/s?w=' . $word . '&dicts=all&main_only=on';
        $url = 'https://e2u.org.ua/s?w=' . $word . '&dicts=all&highlight=on&filter_lines=on';
    } elseif($dic === 'glosbe') {
        $word = str_replace(' ', '%20', $word);
        $url = 'https://uk.glosbe.com/en/uk/' . $word;
    } else {
        echo 'Wrong input!';
        exit();
    }

    // $pageContent = file_get_contents($url); 
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $pageContent = curl_exec($ch);
    curl_close($ch);

    call_user_func_array('process'.$dic, [$pageContent, $word]);
} catch(Error $e) {
    echo $e;
    exit();
}