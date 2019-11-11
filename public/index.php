<?php

require __DIR__ . '/../vendor/autoload.php';

// accomodate php built in cli server
if (php_sapi_name() == 'cli-server') {
    $uri = urldecode(
        parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
    );
    
    if ($uri !== '/' && file_exists(__DIR__ . '/' . $uri)) {
        return false;
    }
}

$bootstrap = new Kernel\Bootstrap;
$bootstrap->run()->send();