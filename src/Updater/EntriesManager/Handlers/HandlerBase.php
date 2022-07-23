<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers;

use AddressFIAS\Storage\StorageBase;
use AddressFIAS\Exception\EntriesHandlerException;

abstract class HandlerBase {

	abstract public static function getFileMask();

	public static function getDependencies(): array {
		return [];
	}

	protected $isFullUpdate = false;

	protected $files = [];

	protected $storage;

	protected $storageTableName;

	public function __construct(array $files, StorageBase $storage){
		$this->files = $files;
		$this->storage = $storage;

		$this->storageTableName = $this->getStorageTable();
	}

	abstract protected function getStorageTable(): string;

	abstract protected function getXmlRowsIdentifier(): string;

	public function setFullUpdate(bool $isFullUpdate){
		$this->isFullUpdate = $isFullUpdate;
	}

	public function isFullUpdate(){
		return $this->isFullUpdate;
	}

	protected function getUpdateTablePrefix(): string {
		return 'update_';
	}

	protected function getUpdateTablePostfix(): string {
		return '';
	}

	public function start(){
		$this->loadUpdateFiles();
		$this->replaceUpdatedData();
	}

	protected function loadUpdateFiles(){
		foreach ($this->files as $file){
			$this->storage->checkConnection();

			if (!$this->storage->loadFromXMLFile($file, $this->storageTableName, $this->getXmlRowsIdentifier())){
				var_dump($this->storage->driver()->errorInfo());
				throw new EntriesHandlerException('XML-file loading error \'' . $file . '\'.');
			}
		}
	}

	protected function replaceUpdatedData(){
		$this->storage->checkConnection();

		if (!$this->isFullUpdate){
			if (!$this->storage->truncateTable($this->storageTableName)){
				throw new EntriesHandlerException('Failed to truncate database table \'' . $this->storageTableName . '\'.');
			}
		}

		#if (!empty($tarr['callback'])){
		#	call_user_func($tarr['callback'], $tarr['table']);
		#}
	}

}
