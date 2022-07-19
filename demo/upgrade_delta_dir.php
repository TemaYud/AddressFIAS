<?php
require_once('init.php');

use AddressFIAS\Updater\Processors\ProcessorGarDelta;

$updater = new \AddressFIAS\Updater();
$updater->processDir('PATH/TO/DELTA/DIR', ProcessorGarDelta::class);
