<?php
namespace AddressFIAS\Storage;

use AddressFIAS\Storage\Drivers\DriverInterface;
use AddressFIAS\Exception\StorageException;

abstract class StorageBase {

	protected $driver;

	public function __construct(DriverInterface $driver){
		$this->driver = $driver;
	}

	public function driver(): DriverInterface {
		return $this->driver;
	}

	public function checkConnection(){
		if (!$this->driver->ping()){
			$this->driver = $this->driver->reconnect();
		}
	}

	//abstract public function createTableLike(string $tbl, string $tbl_like): bool;

	//abstract public function dropTable(string $tbl): bool;

	abstract public function truncateTable(string $tbl): bool;

	//abstract public function fillTableFrom(string $tbl, string $tbl_from): int;

	abstract public function loadFromXMLFile(string $file, string $tbl, string $rows_id): int;

	//abstract public function replaceDataFrom(string $tbl, string $tbl_from): int;

}
