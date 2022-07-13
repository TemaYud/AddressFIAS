<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$updater = new \AddressFIAS\Updater();
#$updater->upgradeFull();
$updater->setProcessFileDir(__DIR__ . DIRECTORY_SEPARATOR . 'tmp');
$updater->upgradeDelta();
