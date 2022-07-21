<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesStorage\EntriesStorageBase;
use AddressFIAS\Storage\StorageBase;
use AddressFIAS\Updater\Processors\Entries\EntryBase;
use AddressFIAS\Exception\ProcessorException;

abstract class ProcessorBase {

	protected $storage;

	protected $entryProcessors = [];

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

				$this->add($files, $entryProcessor);

				$entries = array_diff($entries, $fs);
			}
		}

		$this->run();
	}

	abstract protected function getEntriesProcessors(): array;

	protected function add(array $files, $entryProcessor){
		$this->entryProcessors[] = [
			'files' => $files,
			'entryProcessor' => $entryProcessor,
		];
	}

	protected function run(){
		array_walk($this->entryProcessors, function($arr, $key, $storage){
			$this->startEntryProcessor(new $arr['entryProcessor']($arr['files'], $storage));
		}, $this->storage);
	}

	protected function startEntryProcessor(EntryBase $entryProcessor){
		return $entryProcessor->start();
	}

}
