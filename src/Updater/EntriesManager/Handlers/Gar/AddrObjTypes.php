<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class AddrObjTypes extends HandlerBase {

	public static function getFileMask(): string {
		return '#^AS_ADDR_OBJ_TYPES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui';
	}

	protected function getStorageTable(): string{
		return 'addr_obj_types';
	}

	protected function getXmlRowsIdentifier(): string{
		return 'ADDRESSOBJECTTYPE';
	}

}
