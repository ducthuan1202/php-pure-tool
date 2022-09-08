<?php


session_start();

if (version_compare(PHP_VERSION, '5.6', '<')) {
    exit('php version out of date');
}

if (PHP_SAPI === 'cli') {
    exit('runtime invalid');
}

set_time_limit(0);

header('connection: close');

ignore_user_abort(true);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . './vendor/autoload.php';

set_error_handler('error_handler');

set_exception_handler('exception_handler');

date_default_timezone_set(config('app.timezone'));

if (is_debug()) {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}


$ahref = new \Src\AhrefService();
//$ahref->login();
$ahref->getWorkspace('M%2Bpxc5WJIwz8NcnHwGxX97ZkN3KS%2FPoLyWRcgHj%2B');
//$ahref->getTitle('https://app.ahrefs.com/user/login');