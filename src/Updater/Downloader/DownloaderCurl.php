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
		CURLOPT_BINARYTRANSFER => true,
	];

	public function download($url, $file, $checkExistsFile = true){
		$options = [
			CURLOPT_TIMEOUT => 60 * 60,
			CURLOPT_HEADER => false,
		];

		$fmode = 'wb';
		if ($checkExistsFile && is_file($file)){
			clearstatcache(true, $file);
			$fsize =  filesize($file);
			if ($fsize > 0){
				$headers = $this->getHeaders($url);
				if (isset($headers['content-length'])){
					if ($headers['content-length'] == $fsize){
						return true;
					} elseif ($headers['content-length'] > 0 && (isset($headers['accept-ranges']) && 'bytes' == $headers['accept-ranges'])){
						$fmode = 'ab';

						//$options[CURLOPT_RANGE] = $fsize . '-' . ($headers['content-length'] - 1);
						$options[CURLOPT_RANGE] = $fsize . '-';
					}
				}
			}
		}

		$fh = @fopen($file, $fmode);
		if (false === $fh){
			throw new DownloaderException('Create file error: \'' . $file . '\'.');
		}

		$options[CURLOPT_FILE] = $fh;

		$result = $this->sendRequest($url, $options, static function($ch, $response){
			if (null == ($response = json_decode($response, true))){
				throw new DownloaderException('JSON decode error: ' . json_last_error() . '.');
			}

			return true;
		});

		fclose($fh);

		return $result;
	}

	protected function getHeaders($url){
		$headers = $this->sendRequest($url, [
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => true,
		], static function($ch, $response){
			return $response;
		});

		$headers = explode("\n", trim($headers));
		$result = [];
		foreach ($headers as $i => $h){
			if (0 == $i){
				continue; // HTTP-code line
			}

			list($name, $value) = explode(':', $h, 2);

			$name = trim($name);
			$name = strtolower($name);
			$name = str_replace('_', '-', $name);

			$value = trim($value);
			$value = strtolower($value);

			$result[$name] = $value;
		}

		return $result;
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
