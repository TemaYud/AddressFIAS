<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class Socrbase extends HandlerBase {

	public static function getFileMask(): string {
		return '';
	}

	public static function getDependencies(): array {
		return [
			
		];
	}

	protected function getStorageTable(): string{
		return '';
	}

	protected function getXmlRowsIdentifier(): string{
		return '';
	}

}
