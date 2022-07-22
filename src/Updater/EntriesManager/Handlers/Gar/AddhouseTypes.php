<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class AddhouseTypes extends HandlerBase {

	public static function getFileMask(): string {
		return '#^AS_ADDHOUSE_TYPES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui';
	}

	protected function getStorageTable(): string{
		return 'ADDHOUSE_TYPES';
	}

	protected function getXmlRowsIdentifier(): string{
		return 'HOUSETYPE';
	}

}
