<?php

require __DIR__ . '/../vendor/autoload.php';

$bootstrap = new Kernel\Bootstrap;
$bootstrap->run()->send();