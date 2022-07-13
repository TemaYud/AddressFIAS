<?php
namespace AddressFIAS\Updater\Downloader;

use AddressFIAS\Exception\DownloaderException;

class DownloaderCurl extends DownloaderBase {

	protected $options = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
	];

	protected function downloadFile($url, $file){
		$fh = @fopen($file, 'wb');
		if (false === $fh){
			throw new DownloaderException('File open error: \'' . $file . '\'.');
		}

		$result = $this->sendRequest($url, [
			CURLOPT_TIMEOUT => 60 * 60,
			CURLOPT_HEADER => false,
			CURLOPT_FILE => $fh,
		], function($ch, $response){
			if (null == ($response = json_decode($response, true))){
				throw new DownloaderException('JSON decode error: ' . json_last_error() . '.');
			}

			return true;
		});

		fclose($fh);

		return $result;
	}

	protected function checkExistsFilesize($filesize, $url){
		$urlFilesize = $this->sendRequest($url, [
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => true,
		], function($ch, $response){
			return curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		});

		return ($filesize == $urlFilesize);
	}

	protected function sendRequest($url, array $options, $resultCallback){
		$ch = curl_init();

		if (false === $ch){
			throw new DownloaderException('Error initializing cURL');
		}

		$options = array_replace($this->options, $options);

		$options[CURLOPT_URL] = $url;

		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);

		if (false === $response){
			throw new DownloaderException('cURL error: ' . curl_error($ch) . ' (' . curl_errno($ch) . ').');
		}

		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpStatusCode < 200 || $httpStatusCode >= 300){
			throw new DownloaderException('Wrong HTTP status code: ' . $httpStatusCode . '.');
		}

		$result = call_user_func($resultCallback, $ch, $response);

		curl_close($ch);

		return $result;
	}

}
