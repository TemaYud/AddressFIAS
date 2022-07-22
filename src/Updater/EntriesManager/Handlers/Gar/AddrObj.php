<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class AddrObj extends HandlerBase {

	public static function getFileMask(): string {
		return '#^[0-9]+/AS_ADDR_OBJ_[0-9]+_[0-9a-f\\-]+\\.XML$#ui';
	}

	public static function getDependencies(): array {
		return [
			AddrObjTypes::class,
			ObjectLevels::class,
			#Socrbase::class,
		];
	}

	protected function getStorageTable(): string{
		return 'ADDR_OBJ';
	}

	protected function getXmlRowsIdentifier(): string{
		return 'OBJECT';
	}

}
