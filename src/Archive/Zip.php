<?php
namespace AddressFIAS\Archive;

use AddressFIAS\Archive\Base;
use AddressFIAS\Exception\ArchiveException;

class Zip extends Base {

	protected $arch;

	public function open(){
		$arch = new \ZipArchive();

		if (true !== ($r = $arch->open($this->file))){
			switch ($r){
				case \ZipArchive::ER_EXISTS:
					throw new ArchiveException('File already exists.');
					break;

				case \ZipArchive::ER_INCONS:
					throw new ArchiveException('Zip archive inconsistent.');
					break;

				case \ZipArchive::ER_INVAL:
					throw new ArchiveException('Invalid argument.');
					break;

				case \ZipArchive::ER_MEMORY:
					throw new ArchiveException('Malloc failure.');
					break;

				case \ZipArchive::ER_NOENT:
					throw new ArchiveException('No such file.');
					break;

				case \ZipArchive::ER_NOZIP:
					throw new ArchiveException('Not a zip archive.');
					break;

				case \ZipArchive::ER_OPEN:
					throw new ArchiveException('Can\'t open file.');
					break;

				case \ZipArchive::ER_READ:
					throw new ArchiveException('Read error.');
					break;

				case \ZipArchive::ER_SEEK:
					throw new ArchiveException('Seek error.');
					break;

				default:
					throw new ArchiveException('An unknown error has occurred (' . $r . ')');
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
