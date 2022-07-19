<?php
namespace AddressFIAS\Updater\Processors;

class ProcessorGarFull extends ProcessorBase {

	protected function getFilesMasks(): array {
		return [
			'#^AS_SOCRBASE_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\GarSocrbase::class,
			'#^[0-9]+/AS_ADDR_OBJ_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\GarAddrObj::class,
			'#^[0-9]+/AS_HOUSES_[0-9]+_[0-9a-f\\-]+\\.XML$#ui' => Entries\GarHouse::class,
		];
	}

}
