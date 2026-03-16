<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function logger() : Logger {
    static $log=null;
    if($log===null){
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler(__DIR__.'/../../logs/app.log', Logger::DEBUG));
    }
    return $log;
}
