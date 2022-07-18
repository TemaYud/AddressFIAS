<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Archive\ArchiveBase;
use AddressFIAS\Archive\ArchiveZip;
use AddressFIAS\Updater\Processors\Entries\EntryBase;
use AddressFIAS\Exception\ProcessorException;
use AddressFIAS\Updater\EntriesManager\EntriesManagerBase;

abstract class ProcessorArchiveBase extends ProcessorBase {

	protected $extractDir;

	protected function checkEntriesLocation($entriesLocation){
		return is_file($entriesLocation);
	}

	public function setExtractDir($extractDir){
		$this->extractDir = $extractDir;

		if (!is_dir($this->extractDir)){
			if (!@mkdir($this->extractDir, 0755, true)){
				throw new ProcessorException('Failed to create directory \'' . $this->extractDir . '\'.');
			}
		}
	}

	public function getExtractDir(){
		if (!$this->extractDir){
			$this->setExtractDir(pathinfo($this->archiveFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($this->archiveFile, PATHINFO_FILENAME));
		}
		return $this->extractDir;
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
