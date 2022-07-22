<?php
namespace AddressFIAS\Updater\EntriesManager;

use AddressFIAS\Updater\EntriesStorage\EntriesStorageBase;
use AddressFIAS\Storage\StorageBase;
use AddressFIAS\Updater\EntriesManager\Handlers\HandlerBase;
use AddressFIAS\Exception\EntriesManagerException;

abstract class EntriesManagerBase {

	protected $storage;

	protected $handlers = [];
	protected $handlersProcessed = [];

	protected $isFullUpdate = false;

	public function __construct(StorageBase $storage){
		$this->storage = $storage;
	}

	abstract protected function getEntryHandlers(): array;

	public function setFullUpdate(bool $isFullUpdate){
		$this->isFullUpdate = $isFullUpdate;
	}

	public function isFullUpdate(): bool {
		return $this->isFullUpdate;
	}

	public function process(EntriesStorageBase $entriesStorage){
		$entries = $entriesStorage->getEntries();
		if (false === $entries){
			throw new EntriesManagerException('Error getting entries from EntriesStorage.');
		}

		$entriesProcessors = $this->getEntryHandlers();
		foreach ($entriesProcessors as $handler){
			$mask = $handler::getFileMask();
			$fs = array_filter($entries, function($efile) use($mask){
				return (preg_match($mask, $efile) > 0);
			});

			if ($fs){
				$files = $entriesStorage->toProcess($fs);

				$this->handlerAdd($files, $handler);

				$entries = array_diff($entries, $fs);
			}
		}

		$this->runHandlers();
	}

	protected function handlerAdd(array $files, string $handler){
		$this->handlers[$handler] = [
			'files' => $files,
			'handler' => $handler,
		];
	}

	protected function handlersList(): array {
		return $this->handlers;
	}

	protected function handlerProcessed(string $handler){
		$this->handlersProcessed[$handler] = $this->handlers[$handler];
		unset($this->handlers[$handler]);
	}

	protected function whichHandlersNeedProcessed(array $handlers){
		return array_diff($handlers, array_keys($this->handlersProcessed));
	}

	protected function runHandlers(){
		$handlersCountPrev = null;
		do {
			$handlersList = $this->handlersList();
			$handlersCount = count($handlersList);

			if ($handlersCountPrev === $handlersCount){
				throw new EntriesManagerException('In the list of handlers recursive dependencies (' . implode(', ', array_keys($handlersList)) . ').');
			}
			$handlersCountPrev = $handlersCount;

			foreach ($handlersList as $handler => $harr){
				$dependencies = $harr['handler']::getDependencies();

				$needProcessed = $this->whichHandlersNeedProcessed($dependencies);
				if (!$dependencies || !$needProcessed){
					$this->handlerStartProcess($handler);

					$this->handlerProcessed($handler);
				}
			}
		} while ($handlersCount > 0);
	}

	protected function handlerStartProcess(string $handler){
		$class = new \ReflectionClass($this->handlers[$handler]['handler']);
		$instance = $class->newInstance($this->handlers[$handler]['files'], $this->storage);
		$instance->setFullUpdate($this->isFullUpdate());

		return $instance->start();
	}

}
