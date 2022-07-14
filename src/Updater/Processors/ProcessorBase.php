<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Archive\ArchiveBase;
use AddressFIAS\Archive\ArchiveZip;
use AddressFIAS\Exception\ProcessorException;

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
			$files2processors = $this->getFileProcessors();
			/*uksort($files2processors, static function($a, $b){
				$a = strlen($a);
				$b = strlen($b);

				if ($a == $b){
					return 0;
				}

				return ($a < $b) ? 1 : -1;
			});*/

			foreach ($entries as $entry){
				//$entryFilename = basename($entry->getName());
				$entryFilename = basename($entry['name']);

				foreach ($files2processors as $f => $p){
					if ($this->cmpEntryFileProcessor($f, $entryFilename)){
						if (!$arch->extractEntriy($entry['name'], $this->getExtractDir())){
							throw new ProcessorException('Extract file error. Archive: \'' . $this->archiveFile . '\'. File: \'' . $entry['name'] . '\'.');
						}

						$p;

						break;
					}
				}
				var_dump($entryFilename);
			}
		} catch (\Throwable $e){
			throw $e;
		} finally {
			$arch->close();
		}
	}

	protected function cmpEntryFileProcessor($fp, $entryFilename){
		//return (0 === strncmp($fp, $entryFilename, strlen($fp)));
		return (preg_match('#^' . preg_quote($fp, '#') . '_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$#ui', $entryFilename) > 0);
	}

	abstract protected function getFileProcessors();

}
