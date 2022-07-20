<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use AddressFIAS\Updater;
use AddressFIAS\Updater\Processors\ProcessorGarFull;
use AddressFIAS\Updater\Processors\ProcessorGarDelta;
use AddressFIAS\Storage\StorageMysql;

$updater = new Updater();
$storage = new StorageMysql();

//$updater->processArchive(__DIR__ . '/tmp/20220715_gar_xml.zip', new ProcessorGarFull($storage));

$updater->processDir(__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml', new ProcessorGarDelta($storage));

//$updater->setProcessFileDir(__DIR__ . DIRECTORY_SEPARATOR . 'tmp');
//$updater->upgradeDelta($storage);

/*
$entryProcessor = \AddressFIAS\Updater\Processors\Entries\Gar\ObjTypes('path/to/file.XML', $storage);
$entryProcessor->start();
*/
