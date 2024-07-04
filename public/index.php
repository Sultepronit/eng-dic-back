<?php
declare(strict_types=1);

header('Access-Control-Allow-Origin: *');

require_once 'processE2u.php';
require_once 'processGlosbe.php';

// echo '<pre>';
// print_r($_SERVER);
// print_r($_GET);

try {
    // $target = explode('/', $_SERVER['REQUEST_URI'])[2] ?? '';
    // echo $target;
    // $dic = explode('?', $target)[0];

    $dic = $_GET['dic'] ?? '';

    $word = $_GET['word'] ?? '';

    if($dic === 'e2u') {
        $word = str_replace(' ', '+', $word);
        $url = 'https://e2u.org.ua/s?w=' . $word . '&dicts=all&highlight=on&filter_lines=on';
		// echo $url . '<br>';
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $pageContent = curl_exec($ch);
    curl_close($ch);

    if ($pageContent === false) {
        echo 'Curl error: ' . curl_error($ch);
    }

    // print_r($pageContent);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, 'https://example.com');
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_VERBOSE, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


    // $response = curl_exec($ch);

    // if ($response === false) {
    //     echo 'Curl error: ' . curl_error($ch);
    // } else {
    //     echo $response;
    // }

    // curl_close($ch);


    call_user_func_array('process'.$dic, [$pageContent, $word]);
} catch(Error $e) {
    echo $e;
    exit();
}