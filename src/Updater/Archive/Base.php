<?php
namespace AddressFIAS\Updater\Archive;

abstract class Base {

	protected $file;

	public function __construct($file){
		$this->file = $file;

		$this->open();
	}

	abstract public function open();

	abstract public function close();

	abstract public function getEntries();

	abstract public function extractEntriy($entryname, $path);

}
