<?php
require_once('init.php');

use AddressFIAS\Updater\Processors\ProcessorGarDelta;

$updater = new \AddressFIAS\Updater();
$updater->processArchive('PATH_TO_DELTA_ARCHIVE.zip', ProcessorGarDelta::class);
