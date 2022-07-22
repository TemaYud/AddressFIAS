<?php
namespace AddressFIAS\Updater\EntriesManager\Handlers\Gar;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class House extends HandlerBase {

	public static function getFileMask(): string {
		return '#^[0-9]+/AS_HOUSES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui';
	}

	public static function getDependencies(): array {
		return [
			AddrObj::class,
			HouseTypes::class,
			AddhouseTypes::class,
		];
	}

	protected function getStorageTable(): string{
		return 'HOUSES';
	}

	protected function getXmlRowsIdentifier(): string{
		return 'HOUSE';
	}

}
