<?php
namespace App\Services\Utility;

interface ILogger
{
    
    public static function getLogger();
    public static function debug($message, $data=array());
    public static function info($message, $data=array());
    public static function warning($message, $data=array());
    public static function error($message, $data=array());
    
}

