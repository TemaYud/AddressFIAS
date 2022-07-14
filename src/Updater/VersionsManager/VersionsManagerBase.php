<?php
namespace AddressFIAS\Updater\VersionsManager;

abstract class VersionsManagerBase {

	protected $allDownloadFileInfoURL = 'https://fias.nalog.ru/WebServices/Public/GetAllDownloadFileInfo';
	protected $lastDownloadFileInfoURL = 'https://fias.nalog.ru/WebServices/Public/GetLastDownloadFileInfo';

	abstract protected function getContents($url);

	public function getAll(array $fieldsCheck = ['VersionId', 'GarXMLDeltaURL']){
		$result = $this->getContents($this->allDownloadFileInfoURL);

		if ($fieldsCheck){
			$result = $this->fieldsCheckFilter($result, $fieldsCheck);
		}

		return $result;
	}

	public function getDelta($previousVersionId, array $fieldsCheck = ['VersionId', 'GarXMLDeltaURL']){
		$data = $this->getAll($fieldsCheck);

		$result = [];
		foreach ($data as $farr){
			if ($previousVersionId >= $farr['VersionId']){
				break;
			}
			$result[] = $farr;
		}

		/*$result = array_filter($result, function($farr) use($previousVersionId){
			return ($previousVersionId < $farr['VersionId']);
		});*/

		return $result;
	}

	public function getLast(array $fieldsCheck = ['VersionId', 'GarXMLFullURL', 'GarXMLDeltaURL']){
		$result = $this->getContents($this->allDownloadFileInfoURL);

		if ($fieldsCheck){
			$result = $this->fieldsCheckFilter($result, $fieldsCheck);
		}

		return reset($result);
	}

	protected function fieldsCheckFilter(array $data, array $fieldsCheck){
		return array_filter($data, function($farr) use($fieldsCheck){
			foreach ($fieldsCheck as $f){
				if (empty($farr[$f])){
					return false;
				}
			}
			return true;
		});
	}

}
