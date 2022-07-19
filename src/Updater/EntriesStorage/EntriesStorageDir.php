<?php
namespace AddressFIAS\Updater\EntriesStorage;

class EntriesStorageDir extends EntriesStorageBase {

	protected $iterator;

	protected $fileExtensions = ['xml'];

	public function __construct($filepath){
		parent::__construct($filepath);

		$this->iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->filepath, \RecursiveDirectoryIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS), \RecursiveIteratorIterator::CHILD_FIRST);
	}

	public function getEntries(): array {
		$result = [];
		foreach ($this->iterator as $item){
			if (!$item->isDir() && in_array(strtolower($item->getExtension()), $this->fileExtensions)){
				$result[] = $item->getPathname();
			}
		}

		return $result;
	}

	public function entriesToProcessing(array $entries): array {
		return $entries;
	}

}
