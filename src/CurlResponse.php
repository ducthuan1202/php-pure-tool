<?php

namespace Src;

class CurlResponse
{
    private $data;
    private $info;
    private $error;

    public function __construct($data, $info, $error)
    {
        $this->data = $data;
        $this->info = $info;
        $this->error = $error;
    }

    public function getHttpCode()
    {
        return arr_get($this->info, 'http_code');
    }

    public function getPrimaryIp()
    {
        return arr_get($this->info, 'primary_ip');
    }

    public function getLocalIp()
    {
        return arr_get($this->info, 'local_ip');
    }

    public function getError()
    {
        return $this->error;
    }

    public function getRawData()
    {
        return $this->data;
    }

    public function getData()
    {
        return json_decode($this->error, true);
    }
}
