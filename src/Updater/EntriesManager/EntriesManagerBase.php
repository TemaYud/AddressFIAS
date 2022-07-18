<?php
namespace AddressFIAS\Updater\EntriesManager;

abstract class EntriesManagerBase {

	protected $entries = [];

	public function addEntries(array $entryFiles){
		
	}

	protected function filterEntries(array $entryFiles): array {
		$result = [];
		foreach ($files2processors as $fileMask => $processor){
			$fs = array_filter($entryFiles, function($efile) use($fileMask){
				return (preg_match('#' . $fileMask . '#ui', $efile) > 0);
			});

			if ($fs){
				$result[] = [
					'files' => $fs,
					'processor' => $processor,
				];

				$entryFiles = array_diff($entryFiles, $fs);
			}
		}

		return $result;
	}

	abstract protected function getFilterEntriesMask(): array;

}
