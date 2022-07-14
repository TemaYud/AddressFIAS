<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Updater\Processors\Entries\EntryBase;

class ProcessorGarDelta extends ProcessorBase {

	protected function getEntryProcessors(): array {
		return [
			'^AS_SOCRBASE_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$' => Entries\GarSocrbase::class,
			'^AS_ADDR_OBJ_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$' => Entries\GarAddrObj::class,
			'^AS_HOUSE_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$' => Entries\GarHouse::class,
		];
	}

}
