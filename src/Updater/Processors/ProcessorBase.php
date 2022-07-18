<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\EntriesLocation\EntriesLocationBase;
use AddressFIAS\Exception\ProcessorException;
use AddressFIAS\Updater\EntriesManager\EntriesManagerBase;

abstract class ProcessorBase {

	protected $entriesLocation;

	public function __construct(EntriesLocationBase $entriesLocation){
		$this->entriesLocation = $entriesLocation;
	}

	public function process(){
		$arch = $this->getArchiveHandler();
		$arch->open($this->archiveFile);
		if (false === $arch){
			throw new ProcessorException('Open archive error');
		}

		$entries = $arch->getEntries();
		if (false === $entries){
			throw new ProcessorException('Get archive entries error');
		}

		try {
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
		}
	}

	abstract protected function getEntriesManager(): EntriesManagerBase;

}
