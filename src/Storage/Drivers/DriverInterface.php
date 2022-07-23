<?php
namespace AddressFIAS\Storage\Drivers;

interface DriverInterface {

	public function exec(string $statement);

	public function quote(string $string);

	public function ping(): bool;

	public function reconnect(): DriverInterface;

	public function allowLoadXmlLocalInfile(): bool;

}
