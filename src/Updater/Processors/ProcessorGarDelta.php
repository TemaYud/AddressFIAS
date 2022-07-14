<?php
namespace AddressFIAS\Updater\Processors;

use AddressFIAS\Exception\ProcessorException;

class ProcessorGarDelta extends ProcessorBase {

	protected function getFileProcessors(){
		return [
			'AS_SOCRBASE' => Gar\Socrbase::class,
			'AS_ADDR_OBJ' => Gar\AddrObj::class,
			'AS_HOUSE' => Gar\House::class,
		];
	}

}
