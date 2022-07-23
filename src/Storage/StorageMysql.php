<?php
namespace AddressFIAS\Storage;

class StorageMysql extends StorageBase {

	public function truncateTable(string $tbl): bool {
		$sql = "TRUNCATE TABLE `" . $tbl . "`;";
		return (false !== $this->driver->exec($sql));
	}

	public function loadFromXMLFile(string $file, string $tbl, string $rows_id): int {
		$sql = "LOAD XML LOCAL INFILE " . $this->driver->quote($file) . " REPLACE INTO TABLE `" . $tbl . "` ROWS IDENTIFIED BY " . $this->driver->quote('<' . $rows_id . '>') . ";";
		return $this->driver->exec($sql);
	}

}
