<?php
require_once('init.php');

$updater = new \AddressFIAS\Updater();
$updater->setProcessFileDir(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tmp');
$updater->upgradeDelta();
