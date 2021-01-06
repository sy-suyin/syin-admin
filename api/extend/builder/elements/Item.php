<?php

namespace builder\elements;

class Item {

    protected $config = [];

    public function __construct($config){
        $this->config = $config;
    }

	public function __get($name){
        return isset($this->config[$name]) ? $this->config[$name] : '';
	}
}