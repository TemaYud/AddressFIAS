<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesStorage\EntriesStorageBase;
use AddressFIAS\Exception\ProcessorException;

abstract class ProcessorBase {

	protected $entriesStorage;

	public function __construct(EntriesStorageBase $entriesStorage){
		$this->entriesStorage = $entriesStorage;
	}

	public function process(){
		$entries = $this->entriesStorage->getEntries();
		if (false === $entries){
			throw new ProcessorException('Error getting entries from storage.');
		}

		$processFiles = [];

		$filesMasks = $this->getFilesMasks();
		foreach ($filesMasks as $mask => $processor){
			$fs = array_filter($entries, function($efile) use($mask){
				return (preg_match($mask, $efile) > 0);
			});

			if ($fs){
				$processFiles[] = [
					'files' => $fs,
					'processor' => $processor,
				];

				$entries = array_diff($entries, $fs);
			}
		}

		var_dump($processFiles);
		//var_dump($this->entriesStorage->entriesToProcessing($entries));
	}

	abstract protected function getFilesMasks(): array;

}
