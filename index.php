<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use AddressFIAS\Updater;
use AddressFIAS\Updater\EntriesManager\EntriesManagerGar;
use AddressFIAS\Storage\StorageMysql;

$updater = new Updater();
$storage = new StorageMysql();

$manager = new EntriesManagerGar($storage);
#$manager->setFullUpdate(true);

//$updater->processArchive(__DIR__ . '/tmp/20220715_gar_xml.zip', $manager);

$updater->processDir(__DIR__ . '/tmp/20220715_gar_xml', $manager);

//$updater->setProcessFileDir(__DIR__ . DIRECTORY_SEPARATOR . 'tmp');
//$updater->upgradeDelta($storage);

/*
$entryManager = \AddressFIAS\Updater\EntriesManager\Entries\Gar\ObjTypes('path/to/file.XML', $storage);
$entryManager->start();
*/
