<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Archive\ArchiveBase;
use AddressFIAS\Archive\ArchiveZip;
use AddressFIAS\Updater\Processors\Entries\EntryBase;
use AddressFIAS\Exception\ProcessorException;
use AddressFIAS\Updater\EntriesManager\EntriesManagerBase;

abstract class ProcessorBase {

	protected $archiveFile;
	protected $extractDir;

	protected $archiveHandler;

	public function __construct($archiveFile){
		if (!is_file($archiveFile)){
			throw new ProcessorException('No such file \'' . $archiveFile . '\'.');
		}

		$this->archiveFile = $archiveFile;
	}

	public function setArchiveHandler(ArchiveBase $archiveHandler){
		$this->archiveHandler = $archiveHandler;
	}

	public function getArchiveHandler(){
		if (!$this->archiveHandler){
			$this->setArchiveHandler(new ArchiveZip());
		}
		return $this->archiveHandler;
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
