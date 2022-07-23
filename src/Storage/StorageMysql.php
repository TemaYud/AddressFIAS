<?php
namespace AddressFIAS\Storage;

class StorageMysql extends StorageBase {

	/*public function createTableLike(string $tbl_like, string $tbl): bool {
		$sql = "CREATE TABLE IF NOT EXISTS `" . $tbl . "` LIKE `" . $tbl_like . "`;";
		return (false !== $this->driver->exec($sql));
	}

	public function dropTable(string $tbl): bool {
		$sql = "DROP TABLE IF EXISTS `" . $tbl . "`;";
		return (false !== $this->driver->exec($sql));
	}*/

	public function truncateTable(string $tbl): bool {
		$sql = "TRUNCATE TABLE `" . $tbl . "`;";
		return (false !== $this->driver->exec($sql));
	}

	/*public function fillTableFrom(string $tbl, string $tbl_from): int {
		$sql = "INSERT INTO `" . $tbl . "` SELECT * FROM `" . $tbl_from . "`;";
		return $this->driver->exec($sql);
	}*/

	public function loadFromXMLFile(string $file, string $tbl, string $rows_id): int {
		$sql = "LOAD XML LOCAL INFILE " . $this->driver->quote($file) . " REPLACE INTO TABLE `" . $tbl . "` ROWS IDENTIFIED BY " . $this->driver->quote('<' . $rows_id . '>') . ";";
		return $this->driver->exec($sql);
	}

	/*public function replaceDataFrom(string $tbl, string $tbl_from): int {
		/*$result = true;

		$cnt = $this->countUpdatedData($tbl);
		$parts = 500000;
		for ($i = 0, $l = ceil($cnt / $parts); $i < $l; $i++){
			$sql = "REPLACE INTO `" . $this->_db_table_prefix . $tbl . "`
				SELECT *
				FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`
				LIMIT " . ($i * $parts) . ", " . $parts . ";";
			if (!\Page::$DB->exec($sql)){
				$result = false;
			}
		}

		return $result;*/

		/*$sql = "REPLACE INTO `" . $tbl . "` SELECT * FROM `" . $tbl_from . "`;";
		return $this->driver->exec($sql);
	}*/

}
