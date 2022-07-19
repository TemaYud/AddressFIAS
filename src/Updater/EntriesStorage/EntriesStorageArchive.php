<?php
namespace AddressFIAS\Updater\EntriesStorage;

use AddressFIAS\Archive\ArchiveBase;
use AddressFIAS\Archive\ArchiveZip;
use AddressFIAS\Archive\ArchiveRar;
use AddressFIAS\Exception\ArchiveException;
use AddressFIAS\Exception\EntriesStorageException;

class EntriesStorageArchive extends EntriesStorageBase {

	public static function factoryArchiveHandler($filepath){
		$ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
		switch ($ext){
			case 'zip':
				return new ArchiveZip();
				break;

			case 'rar':
				return new ArchiveRar();
				break;

			default:
				break;
		}

		return false;
	}

	protected $archive;

	protected $extractDir;

	public function __construct(string $filepath, ArchiveBase $archive){
		parent::__construct($filepath);

		$this->archive = $archive;
		$this->archive->open($this->filepath);
	}

	public function getEntries(): array {
		$entries = $this->archive->getEntries();
		if (false === $entries){
			throw new EntriesStorageException('Get archive entries error');
		}

		return array_map(function($earr){
			return $earr['name'];
		}, $entries);
	}

	public function entriesToProcessing(array $entries): array {
		$extractDir = $this->getExtractDir();

		return array_walk($entries, function($entryName) use($extractDir) {
			$entryPath = $extractDir . DIRECTORY_SEPARATOR . $entryName;

			if (!$this->archive->extractEntry($entryName, $extractDir)){
				throw new EntriesStorageException('File extraction error: \'' . $entryPath . '\'.');
			}

			return $entryPath;
		});
	}

	public function setExtractDir($extractDir){
		$this->extractDir = $extractDir;

		if (!is_dir($this->extractDir)){
			if (!@mkdir($this->extractDir, 0755, true)){
				throw new EntriesStorageException('Failed to create directory \'' . $this->extractDir . '\'.');
			}
		}
	}

	public function getExtractDir(){
		if (!$this->extractDir){
			$this->setExtractDir(pathinfo($this->filepath, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($this->filepath, PATHINFO_FILENAME));
		}
		return $this->extractDir;
	}

	public function __destruct(){
		try {
			if ($this->archive){
				$this->archive->close();
			}
		} catch (ArchiveException $e){
		}
	}

}
