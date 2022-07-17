<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Archive\ArchiveBase;
use AddressFIAS\Archive\ArchiveZip;
use AddressFIAS\Updater\Processors\Entries\EntryBase;
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
			$entryFiles = array_map(function($earr){
				return $earr['name'];
			}, $entries);

			var_dump($entryFiles);
			/*$entryFiles = array_combine($entryFiles, array_map(function($efile){
				return basename($efile);
			}, $entryFiles));

			$entryProcessors = $this->getEntryProcessors();
			$fs2p = [];
			foreach ($entryProcessors as $entryMask => $processor){
				$fs = array_filter($entryFiles, function($efile) use($entryMask){
					return (preg_match('#' . $entryMask . '#ui', $efile) > 0);
				});

				if ($fs){
					$fs2p[] = [
						'files' => array_keys($fs),
						'processor' => $processor,
					];

					$entryFiles = array_diff_assoc($entryFiles, $fs);
				}
			}

			var_dump($fs2p);*/
		} catch (\Throwable $e){
			throw $e;
		} finally {
			$arch->close();
		}
	}

	abstract protected function getEntryProcessors(): array;

}
