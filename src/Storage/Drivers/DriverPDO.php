<?php
namespace AddressFIAS\Storage\Drivers;

class DriverPDO extends \PDO implements DriverInterface {

	protected $dsn;
	protected $username;
	protected $password;
	protected $options;

	protected $attributes = [];

	public function __construct(string $dsn, ?string $username = null, ?string $password = null, ?array $options = null){
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		$this->options = $options;

		parent::__construct($this->dsn, $this->username, $this->password, $this->options);
	}

	public function setAttribute($attribute, $value): bool {
		$this->attributes[$attribute] = $value;

		return parent::setAttribute($attribute, $value);
	}

	public function ping(): bool {
		try {
			$this->query('SELECT 1;');
		} catch (\PDOException $e){
			return false;
		}
		return true;
	}

	public function reconnect(): DriverInterface {
		$driver = new static($this->dsn, $this->username, $this->password, $this->options);

		foreach ($this->attributes as $attribute => $value){
			$driver->setAttribute($attribute, $value);
		}

		return $driver;
	}

	public function allowLoadXmlLocalInfile(): bool {
		return false;
	}

}
