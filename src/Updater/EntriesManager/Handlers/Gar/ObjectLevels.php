<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class ObjectLevels extends HandlerBase {

	public static function getFileMask(): string {
		return '#^AS_OBJECT_LEVELS_[0-9]+_[0-9a-f\\-]+\\.XML$#ui';
	}

	protected function getStorageTable(): string{
		return 'OBJECT_LEVELS';
	}

	protected function getXmlRowsIdentifier(): string{
		return 'OBJECTLEVEL';
	}

}
