<?php

namespace syin\builder;

class Base{

	protected $items = [];
	protected $name;

	public function __construct($name){
		$this->name = $name;
	}

	public function add($item){
		$this->items[] = $item;
	}

	public function print(){
		p('-------------------');
		foreach($this->items as $item){
			p($item->getType());
			p($item->getConfig());
		}
	}
}