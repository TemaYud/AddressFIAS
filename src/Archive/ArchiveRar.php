<?php
namespace AddressFIAS\Archive;

use AddressFIAS\Exception\ArchiveException;

class ArchiveRar extends ArchiveBase {

	protected $arch;

	public function __construct(){
		\RarException::setUsingExceptions(true);
	}

	public function open($file){
		try {
			$this->arch = \RarArchive::open($file);

			return true;
		} catch (\RarException $e){
			throw new ArchiveException($e->getMessage(), $e->getCode(), $e);
		}

		return false;
	}

	public function close(){
		if ($this->arch){
			try {
				return $this->arch->close();
			} catch (\RarException $e){
				throw new ArchiveException($e->getMessage(), $e->getCode(), $e);
			}
		}
		return false;
	}

	public function getEntries(){
		if ($this->arch){
			try {
				$result = [];
				foreach ($this->arch->getEntries() as $arr){
					$result[] = [
						'index' => $arr->getIndex(),
						'name' => $arr->getName(),
						'unpacked_size' => $arr->getUnpackedSize(),
						'packed_size' => $arr->getPackedSize(),
						'file_time' => $arr->getFileTime(),
						'crc' => $arr->getCrc(),
					];
				}
				return $result;
			} catch (\RarException $e){
				throw new ArchiveException($e->getMessage(), $e->getCode(), $e);
			}
		}
		return false;
	}

	public function extractEntry($entryname, $path){
		if ($this->arch){
			try {
				if (false !== ($entry = $this->arch->getEntry($entryname))){
					return $entry->extract($path);
				}
			} catch (\RarException $e){
				throw new ArchiveException($e->getMessage(), $e->getCode(), $e);
			}
		}
		return false;
	}

}
