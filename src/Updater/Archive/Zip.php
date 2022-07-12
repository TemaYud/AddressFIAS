<?php
namespace AddressFIAS\Updater\Archive;

use AddressFIAS\Updater\Archive\Base;

class Zip extends Base {

	protected $arch;

	public function open(){
		$arch = new \ZipArchive();

		if (true !== ($r = $arch->open($this->file))){
			switch ($r){
				case \ZipArchive::ER_EXISTS:
					throw new exception('File already exists.', $r);
					break;

				case \ZipArchive::ER_INCONS:
					throw new exception('Zip archive inconsistent.', $r);
					break;

				case \ZipArchive::ER_INVAL:
					throw new exception('Invalid argument.', $r);
					break;

				case \ZipArchive::ER_MEMORY:
					throw new exception('Malloc failure.', $r);
					break;

				case \ZipArchive::ER_NOENT:
					throw new exception('No such file.', $r);
					break;

				case \ZipArchive::ER_NOZIP:
					throw new exception('Not a zip archive.', $r);
					break;

				case \ZipArchive::ER_OPEN:
					throw new exception('Can\'t open file.', $r);
					break;

				case \ZipArchive::ER_READ:
					throw new exception('Read error.', $r);
					break;

				case \ZipArchive::ER_SEEK:
					throw new exception('Seek error.', $r);
					break;

				default:
					throw new exception('An unknown error has occurred (' . $r . ')', $r);
					break;
			}
		}

		$this->arch = $arch;

		return false;
	}

	public function close(){
		if ($this->arch){
			return $this->arch->close();
		}
		return false;
	}

	public function getEntries(){
		if ($this->arch){
			$result = [];
			for ($i = 0; $i < $this->arch->numFiles; $i++){
				if (false === ($arr = $this->arch->statIndex($i))){
					continue;
				}

				$result[] = [
					'name' => $arr['name'],
					'index' => $arr['index'],
					'unpacked_size' => $arr['size'],
					'packed_size' => $arr['comp_size'],
					'file_time' => $arr['mtime'],
					'crc' => $arr['crc'],
				];
			}
			return $result;
		}

		return false;
	}

	public function extractEntriy($entryname, $path){
		if ($this->arch){
			return $this->arch->extractTo($path, $entryname);
		}
		return false;
	}

}
