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

		var_dump($this->entriesStorage->entriesToProcessing($entries));
		/*try {
			$extractDir = $this->getExtractDir();

			$entriesManager = $this->getEntriesManager();
			$entriesManager->addEntries(array_map(function($earr){
				return $earr['name'];
			}, $entries), static function($entryName) use($arch, $extractDir) {
				$entryPath = $extractDir . DIRECTORY_SEPARATOR . $entryName;

				if (!$arch->extractEntry($entryName, $extractDir)){
					throw new ProcessorException('File extraction error: \'' . $entryPath . '\'.');
				}

				return $entryPath;
			});
		} catch (\Throwable $e){
			throw $e;
		} finally {
			$arch->close();
		}*/
	}

	//abstract protected function getEntriesManager(): EntriesManagerBase;

}
