<?php
namespace AddressFIAS\Updater\EntriesStorage;

class EntriesStorageDir extends EntriesStorageBase {

	protected $iterator;

	public function __construct($filepath){
		parent::__construct($filepath);

		$this->iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->filepath, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
	}

	public function getEntries(): array {
		foreach ($this->iterator as $k => $v){
			var_dump($k, $v);
		}

		return [];
	}

	public function entriesToProcessing(array $entries): array {
		return [];
	}

}
