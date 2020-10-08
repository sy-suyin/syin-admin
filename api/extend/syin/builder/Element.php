<?php
namespace syin\builder;

class Element{

	protected $type;
	protected $config;

	public function __construct($type, $config){
		$this->type   = $type;
		$this->config = $config;
	}

	public function getType(){
		return $this->type;
	}

	public function getConfig(){
		return $this->config;
	}
}