<?php
namespace AddressFIAS\Updater\VersionStorage;

class VersionStorageFile extends VersionStorageBase {

	protected function saveCurrentVersionData($versionId, array $info = []){
		return true;
	}

	protected function readCurrentVersionData(){
		$this->versionId = '20220624';
		$this->versionInfo = [];
	}

}
