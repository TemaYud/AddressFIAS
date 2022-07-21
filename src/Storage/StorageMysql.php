<?php
namespace AddressFIAS\Storage;

class StorageMysql extends StorageBase {

	public function createTableLike($tbl, $tbl_like){
		$sql = "CREATE TABLE IF NOT EXISTS `" . $tbl . "` LIKE `" . $tbl_like . "`;";
		return \Page::$DB->exec($sql);
	}

	public function dropTable($tbl){
		$sql = "DROP TABLE IF EXISTS `" . $tbl . "`;";
		return \Page::$DB->exec($sql);
	}

	public function truncateTable($tbl){
		$sql = "TRUNCATE TABLE `" . $tbl . "`;";
		return \Page::$DB->exec($sql);
	}

	public function fillTableFrom($tbl, $tbl_from){
		$sql = "INSERT INTO `" . $tbl . "` SELECT * FROM `" . $tbl_from . "`;";
		return \Page::$DB->exec($sql);
	}

	public function loadFromXML($file, $tbl, $rows_id){
		$sql = "LOAD XML LOCAL INFILE '" . \Page::$DB->escape($file) . "' REPLACE INTO TABLE `" . $tbl . "` ROWS IDENTIFIED BY '<" . \Page::$DB->escape($rows_id) . ">';";
		return \Page::$DB->exec($sql);
	}

}
