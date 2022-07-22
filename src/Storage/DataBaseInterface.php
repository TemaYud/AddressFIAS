<?php
namespace AddressFIAS\Storage;

interface DataBaseInterface {

	abstract public function exec($sql);

	abstract public function quote($string);

}
