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
		$entries = [];
		foreach ($this->iterator as $item){
			if (!$item->isDir() && in_array(strtolower($item->getExtension()), $this->fileExtensions)){
				$entries[] = $this->iterator->getSubPathName();
			}
		}
		return $entries;
	}

	public function toProcess(array $entries): array {
		array_walk($entries, static function(&$f, $k, $d){
			$f = realpath($d . DIRECTORY_SEPARATOR . $f);
		}, $this->filepath);

		return $entries;
	}

}
