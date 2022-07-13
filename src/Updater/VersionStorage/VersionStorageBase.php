<?php
namespace AddressFIAS\Updater\VersionStorage;

use AddressFIAS\Exception\UpdaterException;

abstract class VersionStorageBase {

	protected $versionId = null;
	protected $versionInfo = [];

	public function setCurrentVersionId($versionId, array $info = []){
		$this->versionId = $versionId;
		$this->versionInfo = $info;

		$this->saveCurrentVersionData($this->versionId, $this->versionInfo);
	}

	public function getCurrentVersionId(){
		if (is_null($this->versionId)){
			$this->readCurrentVersionData();

			if (is_null($this->versionId)){
				throw new UpdaterException('Error reading current version data');
			}
		}

		return $this->versionId;
	}

	public function getCurrentVersionInfo(){
		if (is_null($this->versionId)){
			$this->readCurrentVersionData();

			if (is_null($this->versionId)){
				throw new UpdaterException('Error reading current version data');
			}
		}

		return $this->versionInfo;
	}

	abstract protected function saveCurrentVersionData($versionId, array $info = []);

	abstract protected function readCurrentVersionData();

}
