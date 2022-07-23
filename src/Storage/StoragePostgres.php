<?php
namespace AddressFIAS\Storage;

class StorageMysql extends StorageBase {

	public function createTableLike($tbl_like, $tbl){
		$sql = "CREATE TABLE IF NOT EXISTS " . $tbl . " LIKE " . $tbl_like . " INCLUDING ALL;";
		var_dump($sql);
		return true;
		#return \Page::$DB->exec($sql);
	}

	public function dropTable($tbl){
		$sql = "DROP TABLE IF EXISTS " . $tbl . ";";
		var_dump($sql);
		return true;
		#return \Page::$DB->exec($sql);
	}

	public function truncateTable($tbl){
		$sql = "TRUNCATE TABLE " . $tbl . ";";
		var_dump($sql);
		return true;
		#return \Page::$DB->exec($sql);
	}

	public function fillTableFrom($tbl, $tbl_from){
		$sql = "INSERT INTO " . $tbl . " (SELECT * FROM " . $tbl_from . ");";
		var_dump($sql);
		return true;
		#return \Page::$DB->exec($sql);
	}

	public function loadFromXMLFile($file, $tbl, $rows_id){
		//$sql = "LOAD XML LOCAL INFILE '" . \Page::$DB->escape($file) . "' REPLACE INTO TABLE `" . $tbl . "` ROWS IDENTIFIED BY '<" . \Page::$DB->escape($rows_id) . ">';";
		$sql = "LOAD XML LOCAL INFILE '" . $file . "' REPLACE INTO TABLE `" . $tbl . "` ROWS IDENTIFIED BY '<" . $rows_id . ">';";
		var_dump($sql);
		return true;
		#return \Page::$DB->exec($sql);
	}

}
