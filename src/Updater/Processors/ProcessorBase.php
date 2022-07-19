<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesStorage\EntriesStorageBase;
use AddressFIAS\Storage\StorageBase;
use AddressFIAS\Exception\ProcessorException;

abstract class ProcessorBase {

	public static function checkProcessorClassName(string $processor){
		if (!is_subclass_of($processor, self::class)){
			throw new ProcessorException('Processor \'' . $processor . '\' must be name of class that is the instance ' . self::class . '.');
		}
	}

	protected $entriesStorage;

	protected $storage;

	public function __construct(EntriesStorageBase $entriesStorage, StorageBase $storage){
		$this->entriesStorage = $entriesStorage;
		$this->storage = $storage;
	}

	public function process(){
		$entries = $this->entriesStorage->getEntries();
		if (false === $entries){
			throw new ProcessorException('Error getting entries from EntriesStorage.');
		}

		$filesMasks = $this->getFilesMasks();
		foreach ($filesMasks as $mask => $entryProcessor){
			$fs = array_filter($entries, function($efile) use($mask){
				return (preg_match($mask, $efile) > 0);
			});

			if ($fs){
				$files = $this->entriesStorage->toProcess($fs);

				$entryProcessor = new $entryProcessor($files, $this->storage);
				$entryProcessor->start();

				$entries = array_diff($entries, $fs);
			}
		}
	}

	abstract protected function getFilesMasks(): array;

}
