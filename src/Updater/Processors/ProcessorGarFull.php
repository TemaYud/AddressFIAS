<?php
namespace AddressFIAS\Updater\Processors;

class ProcessorGarFull extends ProcessorBase {

	protected function getEntriesProcessors(): array {
		return [
			'#^AS_ADDR_OBJ_TYPES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\Gar\ObjTypes::class,
			'#^AS_OBJECT_LEVELS_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\Gar\ObjectLevels::class,
			'#^AS_HOUSE_TYPES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\Gar\HouseTypes::class,
			'#^AS_ADDHOUSE_TYPES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\Gar\AddhouseTypes::class,

			'#^[0-9]+/AS_ADDR_OBJ_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\Gar\AddrObj::class,
			'#^[0-9]+/AS_HOUSES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\Gar\House::class,
		];
	}

}
