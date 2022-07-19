<?php
namespace AddressFIAS\Updater\Processors\Entries;

use AddressFIAS\Storage\StorageBase;

abstract class EntryBase {

	protected $files = [];

	protected $storage;

	public function __construct(array $files, StorageBase $storage){
		$this->files = $files;
		$this->storage = $storage;
	}

	public function start(){
		#
	}

}
