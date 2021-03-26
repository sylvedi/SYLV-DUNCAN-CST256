<?php
namespace App\Services\Utility;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class FileLogger implements ILoggerService
{
    
    private static $logger = null;
    private static $instance = null;
    
    public function __construct(){
        self::$logger = new Logger('MyApp');
        $stream = new StreamHandler('storage/logs/connectapp.log', Logger::DEBUG);
        $stream->setFormatter(new LineFormatter("%datetime% : %level_name% : %message% %context%\n", "g:iA n/j/Y"));
        self::$logger->pushHandler($stream);
    }
    
    public function getInstance() {
        if(self::$instance === null) {
            self::$instance = new FileLogger();
        }
        return self::$instance;
    }
    
    public function debug($message, $data = [])
    {
        self::$logger->debug($message, $data);
    }
    
    public function warning($message, $data = [])
    {
        self::$logger->warning($message, $data);
    }
    
    public function warn($message, $data = [])
    {
        self::$logger->warning($message, $data);
    }
    
    public function error($message, $data = [])
    {
        self::$logger->error($message, $data);
    }
    
    public function info($message, $data = [])
    {
        self::$logger->info($message, $data);
    }
    
}

