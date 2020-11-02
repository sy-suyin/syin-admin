<?php

namespace syin\builder;

class Base{

	protected $items = [];
	protected $name;
	protected $class_name;

	public function __construct($name){
		$this->name = $name;
		$this->class_name = ucfirst($this->camelize($name));
	}

	public function add($item){
		$this->items[] = $item;
	}

	/**
	 * 下划线转驼峰
	 */
	function camelize($uncamelized_words,$separator='_'){
		$uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
   		return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

	/**
     * 驼峰命名转下划线命名
     */
    function uncamelize($camelCaps,$separator='_'){
    	return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}