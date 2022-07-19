<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use AddressFIAS\Updater;
use AddressFIAS\Updater\Processors\ProcessorGarDelta;

$updater = new Updater();
$updater->processDir(__DIR__ . '/tmp/20220719_gar_delta_xml', ProcessorGarDelta::class);
