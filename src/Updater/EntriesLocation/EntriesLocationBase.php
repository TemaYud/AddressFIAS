<?php
namespace AddressFIAS\Updater\EntriesLocation;

abstract class EntriesLocationBase {

	abstract public function getEntries();

	abstract public function entriesToProcessing($entries);

}
