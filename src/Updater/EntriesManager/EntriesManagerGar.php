<?php
namespace AddressFIAS\Updater\EntriesManager;

use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;

class EntriesManagerGar extends EntriesManagerBase {

	protected function getEntryHandlers(): array {
		return [
			Handlers\Gar\AddrObjTypes::class,
			/*Handlers\Gar\ObjectLevels::class,
			Handlers\Gar\AddrObj::class,
			Handlers\Gar\House::class,
			Handlers\Gar\HouseTypes::class,
			Handlers\Gar\AddhouseTypes::class,*/
		];
	}

}
