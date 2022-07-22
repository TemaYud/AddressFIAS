<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('log_errors', true);

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use AddressFIAS\Updater;
use AddressFIAS\Updater\EntriesManager\EntriesManagerGar;
use AddressFIAS\Updater\EntriesManager\Handlers\Gar\AddrObj as AddrObjHandler;
use AddressFIAS\Storage\StorageMysql;

$updater = new Updater();
$storage = new StorageMysql();

$manager = new EntriesManagerGar($storage);
#$manager->setFullUpdate(true);

//$updater->processArchive(__DIR__ . '/tmp/20220715_gar_xml.zip', $manager);

$updater->processDir(__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml', $manager);

//$updater->setProcessFileDir(__DIR__ . DIRECTORY_SEPARATOR . 'tmp');
//$updater->upgradeDelta($storage);

/*
$addrObjHandler = new AddrObjHandler([
	__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml/01/AS_ADDR_OBJ_20220718_1f05d838-4b37-49c3-ab75-d10d316cd146.XML',
	__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml/02/AS_ADDR_OBJ_20220718_de5f06e1-695d-4bb1-a1bb-d5d24463aa5b.XML',
	__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml/03/AS_ADDR_OBJ_20220718_4bd90010-97e5-4cbd-ac63-1f97700595ea.XML',
	__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml/92/AS_ADDR_OBJ_20220718_d44127cd-7533-4ad9-baf8-8a217bd3f0b6.XML',
	__DIR__ . '/tmp/process_dir_20220719_gar_delta_xml/99/AS_ADDR_OBJ_20220718_20b00c8c-5a47-4279-8564-572a5b4cb0a1.XML',
], $storage);
$addrObjHandler->setFullUpdate(false);
$addrObjHandler->start();
*/
