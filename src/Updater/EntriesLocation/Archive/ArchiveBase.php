<?php
namespace AddressFIAS\Archive;

abstract class ArchiveBase {

	abstract public function open($file);

	abstract public function close();

	abstract public function getEntries();

	abstract public function extractEntry($entryname, $path);

}
