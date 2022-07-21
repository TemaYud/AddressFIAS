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
		if (!($r = $this->storage->createTableLike($tarr['table'], 'update_' . $tarr['table']))){
			trigger_error('Failed to create update database table');
			unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
			continue 2;
		}

		if (!$this->storage->truncateTable($tarr['table'])){
			trigger_error('Failed to truncate update database table');
			unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
			continue 2;
		}

		#if (!$this->storage->fillTableFrom('update_' . $tarr['table'], $tarr['table'])){
		#	trigger_error('Failed to fill update database table');
		#	unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
		#	continue 2;
		#}

		$this->storage->loadFromXML(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename, 'update_' . $tarr['table'], $tarr['rows_identified']);

		/*if (!\Page::$DB->ping()){
			\Page::$DB->reconnect();
		}

		if (!$this->storage->replaceUpdatedData($tarr['table'])){
			trigger_error('Failed to replace updated data');
		}

		if (!empty($tarr['callback'])){
			call_user_func($tarr['callback'], $tarr['table']);
		}*/

		$this->storage->dropTable('update_' . $tarr['table']);
	}

}
