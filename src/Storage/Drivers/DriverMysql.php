<?php
namespace AddressFIAS\Storage\Drivers;

class DriverMysql extends DriverPDO {

	public function allowLoadXmlLocalInfile(): bool {
		return $this->getAttribute(\PDO::MYSQL_ATTR_LOCAL_INFILE);
	}

}
