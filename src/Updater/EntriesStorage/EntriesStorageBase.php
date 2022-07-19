<?php
namespace AddressFIAS\Updater\EntriesStorage;

abstract class EntriesStorageBase {

	protected $filepath;

	public function __construct(string $filepath){
		$this->filepath = $filepath;
	}

	abstract public function getEntries(): array;

	abstract public function toProcess(array $entries): array;

}
