<?php
namespace AddressFIAS\Updater\Downloader;

abstract class DownloaderBase {

	abstract public function download($url, $file, $checkExistsFile = true);

}
