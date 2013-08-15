<?php

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ?
    getenv('APPLICATION_ENV') :
    'production'));

if(APPLICATION_ENV == 'development'){
    error_reporting(E_ALL | E_STRICT);
    ini_set("display_errors", 1);
}

require_once 'web-wake-server/WebWakeServer.php';
$wakeup = new WebWakeServer();