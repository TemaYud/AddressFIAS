<?php
namespace AddressFIAS\Updater\EntriesStorage;

class EntriesStorageDir extends EntriesStorageBase {

	protected $directory;

	public function __construct($filepath){
		parent::__construct($filepath);

		$this->directory = new \DirectoryIterator($this->filepath);
	}

	public function getEntries(): array {
		return [];
	}

	public function entriesToProcessing(array $entries): array {
		return [];
	}

}
