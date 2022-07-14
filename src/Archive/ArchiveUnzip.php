<?php
namespace AddressFIAS\Archive;

class ArchiveUnzip extends ArchiveBase {

	protected $file;

	public function open($file){
		if (!is_file($file)){
			throw new ArchiveException('No such file \'' . $file . '\'.');
		}

		$this->file = $file;

		return true;
	}

	public function close(){
		return true;
	}

	public function getEntries(){
		if (!$this->file){
			return false;
		}

		$cmd = 'unzip -Z1 ' . escapeshellarg($this->file);
		exec($cmd, $entries, $return_var);

		if (0 !== $return_var){
			return false;
		}

		$result = [];
		foreach ($entries as $index => $name){
			$result[] = [
				'name' => $name,
				'index' => $index,
				'unpacked_size' => null,
				'packed_size' => null,
				'file_time' => null,
				'crc' => null,
			];
		}

		return $result;
	}

	public function extractEntriy($entryname, $path){
		if (!$this->file){
			return false;
		}

		$cmd = 'unzip -p ' . escapeshellarg($this->file) . ' ' . escapeshellarg($entryname) . ' > ' . escapeshellarg($path . $entryname);
		exec($cmd, $entries, $return_var);

		return (0 === $return_var);
	}

}
