<?php
require_once('init.php');

use AddressFIAS\Updater\Processors\ProcessorGarFull;

$updater = new \AddressFIAS\Updater();
$updater->processDir('PATH/TO/FULL/DIR', ProcessorGarFull::class);
