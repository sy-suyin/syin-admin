<?php

namespace syin\input;

class IntInput {

	public function execute($field, $params, $default = null){
		$val = isset($params[$field]) ? intval($params[$field]) : $default;

		// 设置默认值
		if(is_null($val)){
			$val = 0;
		}

		return $val;
	}
}