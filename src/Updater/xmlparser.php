<?php
/* Кодировка файла UTF-8 */

namespace libs\address\addrobj\update;

class xmlparser {

	private $file;
	private $fh;

	private $rowsIdentifier;
	private $callback;

	private $parser;

	public function __construct($file){
		$this->file = $file;

		if (!($this->fh = fopen($this->file, 'r'))){
			throw new \libs\address\addrobj\update\exception('Open file error: ' . $this->file . '.');
		}

		$this->parser = xml_parser_create('UTF-8');

		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'tagOpen', 'tagClose');
		xml_set_character_data_handler($this->parser, 'cData');
	}

	public function __destruct(){
		if ($this->parser){
			xml_parser_free($this->parser);
		}
		if ($this->fh){
			fclose($this->fh);
		}
	}

	public function parse($rowsIdentifier, $callback){
		$this->rowsIdentifier = $rowsIdentifier;
		$this->callback = $callback;

		while (($data = fread($this->fh, 16384))){
			xml_parse($this->parser, $data);
		}
	}

	public function tagOpen($parser, $tag, $attributes){
		if (0 !== strcasecmp($this->rowsIdentifier, $tag)){
			return;
		}

		call_user_func($this->callback, $attributes);
	}

	public function cData($parser, $cdata){
		//var_dump(__LINE__, $cdata);
	}

	public function tagClose($parser, $tag){
		//var_dump(__LINE__, $tag);
	}

}
