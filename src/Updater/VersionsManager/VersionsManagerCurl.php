<?php
namespace AddressFIAS\Updater\VersionsManager;

use AddressFIAS\Exception\UpdaterException;

class VersionsManagerCurl extends VersionsManagerBase {

	public function __construct(){
	}

	protected function getContents($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);

		if (false === $result){
			throw new UpdaterException('cURL error: ' . curl_error($ch) . ' (' . curl_errno($ch) . ').');
		}

		curl_close($ch);

		if (null == ($result = json_decode($result, true))){
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

}
