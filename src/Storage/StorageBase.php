<?php
namespace AddressFIAS\Storage;

use AddressFIAS\Storage\Drivers\DriverInterface;
use AddressFIAS\Exception\StorageException;

abstract class StorageBase {

	protected $driver;

	public function __construct(DriverInterface $driver){
		$this->driver = $driver;
	}

	abstract public function createTableLike($tbl, $tbl_like);

	abstract public function dropTable($tbl);

	abstract public function truncateTable($tbl);

	abstract public function fillTableFrom($tbl, $tbl_from);

	abstract public function loadFromXML($file, $tbl, $rows_id);

	public function ping(){
		return true;
	}

	public function reconnect(){
		return true;
	}

	public function isLoadXmlLocalInfile(){
		return false;
	}

}
