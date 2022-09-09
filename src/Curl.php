<?php

namespace Src;

class Curl
{

    public function get($url, $options = [], $payload = [])
    {
        if (is_array($payload) && count($payload) > 0) {
            $url .= '?' . http_build_query($payload);
        }

        return $this->run($url, $options);
    }

    public function post($url, $options = [], $payload = [])
    {
        if (is_array($payload) && count($payload) > 0) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        return $this->run($url, $options);
    }

    private function run($url, $options)
    {
        $curlOptions = [
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_USERAGENT => Request::USERAGENT_EDGE,
        ];

        if (is_array($options) && count($options) > 0) {
            foreach($options as $key => $val){
                $curlOptions[$key] = $val;
            }
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, $curlOptions);
        $data = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return new CurlResponse($data, $info, $error);
    }
}
