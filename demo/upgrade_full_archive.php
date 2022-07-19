<?php
require_once('init.php');

use AddressFIAS\Updater\Processors\ProcessorGarFull;

$updater = new \AddressFIAS\Updater();
$updater->processArchive('PATH_TO_FULL_ARCHIVE.zip', ProcessorGarFull::class);
