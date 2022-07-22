<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class HouseTypes extends HandlerBase {

	public static function getFileMask(): string {
		return '#^AS_HOUSE_TYPES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui';
	}

	protected function getStorageTable(): string{
		return 'HOUSE_TYPES';
	}

	protected function getXmlRowsIdentifier(): string{
		return 'HOUSETYPE';
	}

}
