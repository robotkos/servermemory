<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$loader = require __DIR__.'/vendor/autoload.php';

$run = new \serverMonitor\serverMonitorController();
$sLoad = $run->readServerSpace();
$run->checkServerSpace($sLoad);
$run->updateStatusFiles($sLoad);
