<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$updater = new \AddressFIAS\Updater();
$updater->setProcessFileDir(__DIR__ . DIRECTORY_SEPARATOR . 'tmp');
#$updater->upgradeFull();
$updater->upgradeDelta();
