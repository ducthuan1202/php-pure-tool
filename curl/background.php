<?php

set_time_limit(0);
header('connection: close');
ignore_user_abort(true);
// code run background 

file_put_contents(sprintf("header_%s.json", date('Ymd')), json_encode(getallheaders(), JSON_PRETTY_PRINT));
file_put_contents(sprintf("post_%s.json", date('Ymd')), json_encode($_POST, JSON_PRETTY_PRINT));

$data = getTodo();
file_put_contents(sprintf("todo_%s.json", date('Ymd')), $data);

function arr_get($arr, $key, $default = null)
{
    return array_key_exists($key, $arr) ? $arr[$key] : $default;
}

function getTodo()
{
    $limit = arr_get($_POST, 'limit', 10);
    $page = arr_get($_POST, 'page', 1);

    $query = http_build_query([
        '_limit' => $limit,
        '_page' => $page,
    ]);

    $url = 'https://jsonplaceholder.typicode.com/todos?' . $query;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_TIMEOUT => 3,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
        CURLOPT_HEADER => false,
    ]);
    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}
