<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesManager\EntriesManagerBase;
use AddressFIAS\Updater\EntriesManager\EntriesManagerGarDelta;

class ProcessorGarDelta extends ProcessorBase {

	protected function getEntriesManager(): EntriesManagerBase {
		return new EntriesManagerGarDelta();
	}

}
