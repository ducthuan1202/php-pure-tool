<?php

use Src\Config;
use Src\Request;
use Src\Response;

if (!function_exists('get_ip')) {
    function get_ip()
    {
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($keys as $key) :
            if (array_key_exists($key, $_SERVER)) :
                return $_SERVER[$key];
            endif;
        endforeach;

        return 'unknown';
    }
}

if (!function_exists('get_useragent')) {
    function get_useragent()
    {
        return arr_get($_SERVER, 'HTTP_USER_AGENT');
    }
}

if (!function_exists('get_method')) {
    function get_method()
    {
        return strtoupper(arr_get($_SERVER, 'REQUEST_METHOD', Request::METHOD_GET));
    }
}

if (!function_exists('get_uri')) {
    function get_uri()
    {
        return arr_get($_SERVER, 'REQUEST_URI');
    }
}

if (!function_exists('get_query_string')) {
    function get_query_string()
    {
        return arr_get($_SERVER, 'QUERY_STRING');
    }
}

if (!function_exists('get_param')) {
    function get_param($key)
    {
        if (empty($key)) :
            return $_GET[$key];
        endif;

        return arr_get($_GET, $key);
    }
}

if (!function_exists('post_param')) {
    function post_param($key)
    {
        if (empty($key)) :
            return $_POST[$key];
        endif;

        return arr_get($_POST, $key);
    }
}

if (!function_exists('is_get_method')) {
    function is_get_method()
    {
        return get_method() === Request::METHOD_GET;
    }
}

if (!function_exists('is_post_method')) {
    function is_post_method()
    {
        return get_method() === Request::METHOD_POST;
    }
}

if (!function_exists('is_put_method')) {
    function is_put_method()
    {
        return get_method() === Request::METHOD_PUT;
    }
}

if (!function_exists('is_delete_method')) {
    function is_delete_method()
    {
        return get_method() === Request::METHOD_DELETE;
    }
}

if (!function_exists('is_debug')) {
    function is_debug()
    {
        return config('app.debug');
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to($url, $code = null)
    {
        if ($code === Response::HTTP_PERMANENTLY_REDIRECT) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        header('Location: ' . $url);
        exit();
    }
}

if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        $configs = Config::getInstance();
        if (empty($key)) {
            return $configs;
        }
        return arr_get($configs, $key, $default);
    }
}

if (!function_exists('arr_get')) {
    function arr_get(array $arr, $key, $def = null)
    {
        if (array_key_exists($key, $arr)) {
            return $arr[$key];
        }

        if (is_numeric(strpos($key, '.'))) {

            $keys = explode('.', $key);

            $firstKey = current($keys);
            $lastKey = $keys[count($keys) - 1];

            if (!array_key_exists($firstKey, $arr)) {
                return $def;
            }

            $tmp = $arr[$firstKey];

            foreach ($keys as $k) :

                if ($k === $firstKey) {
                    continue;
                }

                if ($k === $lastKey) {
                    return $tmp[$k];
                }

                if (!array_key_exists($k, $tmp)) {
                    return $def;
                }

                $tmp = $tmp[$k];

            endforeach;

            return $tmp;
        }

        return $def;
    }
}

if (!function_exists('exception_handler')) {
    function exception_handler($exception)
    {
        if (!is_debug()) {
            exit('Ops, something went wrong!');
        }

        echo sprintf(
            '<h3>Exception (#%s): [%s]</h3>',
            $exception->getCode(),
            $exception->getMessage()
        );

        echo sprintf(
            '<p>File: %s at line %s</p>',
            $exception->getFile(),
            $exception->getLine()
        );

        echo '<kbd>' . $exception->getTraceAsString() . '</kbd>';

        exit();
    }
}

if (!function_exists('error_handler')) {
    function error_handler($errno, $errstr, $errfile, $errline)
    {
        if (!is_debug()) {
            exit('Ops, something went wrong!');
        }

        echo sprintf('<h3>Error (#%s): [%s]</h3>', $errno, $errstr);

        echo sprintf("<p>File: %s at line %s</p>", $errfile, $errline);

        exit();
    }
}

if (!function_exists('get_title_from_txt')) {
    function get_title_from_txt($txt)
    {
        return preg_match('/<title[^>]*>(.*?)<\/title>/ims', $txt, $matches) ? $matches[1] : null;
    }
}
