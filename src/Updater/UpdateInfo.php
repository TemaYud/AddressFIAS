<?php
namespace AddressFIAS\Updater;

class UpdateInfo {

	protected $versionID = 0;

	public function getLastVersionId(){
		return '20220624';
		if (!$this->versionID){
			$sql = "SELECT `fias_update_data_value` FROM `fias_update_data` WHERE `fias_update_data_key` = 'VersionId' LIMIT 1;";
			$res = \Page::$DB->query($sql);
			if (!$res->num_rows > 0){
				return false;
			}

			$this->versionID = $res->get_one();
		}
		return $this->versionID;
	}

	public function setLastVersionId($VersionId, array $data = []){
		$this->versionID = $VersionId;

		$r = $this->setUpdateInfo('VersionId', $VersionId);

		foreach ($data as $k => $v){
			$this->setUpdateInfo($k, $v);
		}

		return $r;
	}

	public function setUpdateInfo($fias_update_data_key, $fias_update_data_value){
		/*$sql = "UPDATE `fias_update_data` SET `fias_update_data_value` = '" . \Page::$DB->escape($fias_update_data_value) . "' WHERE `fias_update_data_key` = '" . \Page::$DB->escape($fias_update_data_key) . "' LIMIT 1;";
		return \Page::$DB->exec($sql);*/
	}

}
?>
