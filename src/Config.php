<?php

namespace Src;

use Symfony\Component\Yaml\Yaml;

class Config
{

    private static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            $configFilePath = ROOT_PATH . '/configs.yaml';
            if (file_exists($configFilePath)) {
                self::$instance = Yaml::parseFile($configFilePath);
            }
        }
        return self::$instance;
    }
}
