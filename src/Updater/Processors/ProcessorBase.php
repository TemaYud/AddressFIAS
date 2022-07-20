<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesStorage\EntriesStorageBase;
use AddressFIAS\Storage\StorageBase;
use AddressFIAS\Exception\ProcessorException;

abstract class ProcessorBase {

	protected $storage;

	public function __construct(StorageBase $storage){
		$this->storage = $storage;
	}

	public function process(EntriesStorageBase $entriesStorage){
		$entries = $entriesStorage->getEntries();
		if (false === $entries){
			throw new ProcessorException('Error getting entries from EntriesStorage.');
		}

		$filesMasks = $this->getEntriesProcessors();
		foreach ($filesMasks as $mask => $entryProcessor){
			$fs = array_filter($entries, function($efile) use($mask){
				return (preg_match($mask, $efile) > 0);
			});

			if ($fs){
				$files = $entriesStorage->toProcess($fs);

				$entryProcessor = new $entryProcessor($files, $this->storage);
				$entryProcessor->start();

				$entries = array_diff($entries, $fs);
			}
		}
	}

	abstract protected function getEntriesProcessors(): array;

}
