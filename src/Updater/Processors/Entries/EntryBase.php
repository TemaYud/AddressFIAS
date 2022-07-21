<?php
namespace AddressFIAS\Updater\Processors\Entries;

use AddressFIAS\Storage\StorageBase;

abstract class EntryBase {

	protected $files = [];

	protected $storage;

	protected $isFullUpdate = false;

	public function __construct(array $files, StorageBase $storage){
		$this->files = $files;
		$this->storage = $storage;
	}

	public function setFullUpdate(bool $isFullUpdate){
		$this->isFullUpdate = $isFullUpdate;
	}

	public function isFullUpdate(){
		return $this->isFullUpdate;
	}

	public function start(){
		#
	}

}
