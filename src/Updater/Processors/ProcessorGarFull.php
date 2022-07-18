<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesManager\EntriesManagerBase;
use AddressFIAS\Updater\EntriesManager\EntriesManagerGarFull;

class ProcessorGarFull extends ProcessorBase {

	protected function getEntriesManager(): EntriesManagerBase {
		return new EntriesManagerGarFull();
	}

}
