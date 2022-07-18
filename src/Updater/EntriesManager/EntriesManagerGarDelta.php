<?php
namespace AddressFIAS\Updater\EntriesManager;

class EntriesManagerGarDelta extends EntriesManagerBase {

	public static function filterEntries(array $entryFiles): array {
		/*return [
			'^AS_SOCRBASE_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$' => Entries\GarSocrbase::class,
			'^AS_ADDR_OBJ_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$' => Entries\GarAddrObj::class,
			'^AS_HOUSES_[0-9]{8}_[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\\.XML$' => Entries\GarHouse::class,
		];*/

		return [];
	}

}
