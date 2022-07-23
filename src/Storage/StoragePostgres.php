<?php
namespace AddressFIAS\Storage;

class StorageMysql extends StorageBase {

	public function truncateTable($tbl){
		$sql = "TRUNCATE TABLE " . $tbl . ";";
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
