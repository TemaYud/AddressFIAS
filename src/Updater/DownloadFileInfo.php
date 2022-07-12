<?php
namespace AddressFIAS\Updater;

use AddressFIAS\Exception\UpdaterException;

class DownloadFileInfo {

	protected $httpClient;

	protected $allDownloadFileInfoURL = 'https://fias.nalog.ru/WebServices/Public/GetAllDownloadFileInfo';
	protected $lastDownloadFileInfoURL = 'https://fias.nalog.ru/WebServices/Public/GetLastDownloadFileInfo';

	protected $processedVersionID = 0;

	public function __construct(){
	}

	public function setHttpClient(\Psr\Http\Client\ClientInterface $httpClient){
		$this->httpClient = $httpClient;
	}

	public function getHttpClient(){
		return $this->httpClient;
	}

	protected function getContents($url){
		$res = $this->getHttpClient()->request('GET', $url);

		if (null == ($result = json_decode($res->getBody(), true))){
			throw new UpdaterException('JSON decode error: ' . json_last_error() . '.');
		}

		/*usort($result, function($a, $b){
			if ($a['VersionId'] == $b['VersionId']){
				return 0;
			}
			return ($a['VersionId'] < $b['VersionId']) ? -1 : 1;
		});*/

		return $result;
	}

	public function getAll($previousVersionId = 0, $checkFunc = null){
		$result = $this->getContents($this->allDownloadFileInfoURL);

		if ($previousVersionId){
			$data = $result;
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
		}

		if (is_null($checkFunc)){
			$checkFunc = function($farr){
				return !empty($farr['GarXMLDeltaURL']);
			};
		}
		$result = array_filter($result, $checkFunc);

		return $result;
	}

	public function getLast($checkFunc = null){
		$data = $this->getContents($this->allDownloadFileInfoURL);

		if (is_null($checkFunc)){
			$checkFunc = function($farr){
				return (!empty($farr['GarXMLFullURL']) && !empty($farr['GarXMLDeltaURL']));
			};
		}

		foreach ($data as $farr){
			$r = call_user_func($checkFunc, $farr);
			if (false != $r){
				return $farr;
			}
		}
		return [];
	}

}
?>
