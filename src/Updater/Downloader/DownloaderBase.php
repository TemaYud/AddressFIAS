<?php
namespace AddressFIAS\Updater\Downloader;

abstract class DownloaderBase {

	public function download($url, $file, $checkExistsFile = true){
		if (is_file($file)){
			if ($checkExistsFile && $this->checkExistsFile($file, $url)){
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

	protected function checkExistsFile($file, $url){
		return $this->checkExistsFilesize($this->getLocalFilesize($file), $url);
	}

	abstract protected function checkExistsFilesize($localFilesize, $url);

}
