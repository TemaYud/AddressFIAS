<?php
namespace AddressFIAS\Updater\Processors;

class ProcessorGarDelta extends ProcessorGarFull {

	protected function getEntriesProcessors(): array {
		return parent::getEntriesProcessors();
	}

	protected function startEntryProcessor(EntryBase $entryProcessor){
		return $entryProcessor->start();
	}

}
