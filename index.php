<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$mdl = new \AddressFIAS\Updater();
#$mdl->upgradeFull();
$mdl->upgradeDelta();
