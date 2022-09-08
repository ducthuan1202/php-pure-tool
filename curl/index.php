<?php

/**
 * tip để thực hiện 1 job chạy dưới nền đó là gọi sang 1 url
 * và bên trang đó, set:
 * set_time_limit(0); // bỏ set timeout
 * header('connection: close'); // đặt header connection là close 
 * ignore_user_abort(true); // bỏ qua kết nối với máy khách
 */
$url = 'http://localhost/ndt/curl/background.php';
$postFields = [
    'keyword' => 'php',
    'sort_field' => 'id',
    'sort_direction' => 'asc',
    'limit' => 50,
    'page' => 1,
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_NOSIGNAL => true,
    CURLOPT_TIMEOUT_MS => 50,
    CURLOPT_VERBOSE => true,
    CURLOPT_POSTFIELDS => http_build_query($postFields),
    CURLOPT_HEADER => 1,
    CURLOPT_FRESH_CONNECT => true,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
    CURLOPT_REFERER => 'http://google.com.vn',
    CURLOPT_HTTPHEADER => [
        'Host: thuannd.info',
        'Cookie: pid=phpPid',
        'access-token: jd3t2-qh36r'
    ]
]);

curl_exec($ch);
curl_close($ch);

echo 'done';
