<?php
namespace AddressFIAS\Updater\Downloader;

abstract class DownloaderBase {

	public function download($url, $file, $checkExistsFile = true){
		if (is_file($file)){
			if ($checkExistsFile && $this->checkFilesize($this->getLocalFilesize($file), $url)){
				return $file;
			}

			unlink($file);
		}

		return $this->downloadFile($url, $file);
	}

	protected function getLocalFilesize($file){
		//clearstatcache(true, $file);
		return filesize($file);
	}

	abstract protected function downloadFile($url, $file);

	abstract protected function checkFilesize($localFilesize, $url);

}
