<?php
namespace AddressFIAS\Updater\Downloader;

use AddressFIAS\Exception\UpdaterException;

class DownloaderCurl extends DownloaderBase {

	protected function downloadFile($url, $file){
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

	protected function checkFilesize($filesize, $url){
		$ch = curl_init();

        if (false === $ch){
            throw new Exception();
        }

		$options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FILE => $this->openLocalFile($localFile, 'wb'),
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => true,
        ];

		curl_setopt_array($ch, $options);

		$result = curl_exec($ch);

		if (false === $result){
			throw new Exception('cURL error: ' . curl_error($ch) . ' (' . curl_errno($ch) . ').');
		}

		$urlFilesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		curl_close($ch);

		return ($filesize == $urlFilesize);
	}

}
