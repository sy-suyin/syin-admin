<?php

namespace syin\input;

class FloatInput {

	public function execute($field, $params, $default = null){
		$val = isset($params[$field]) ? $params[$field] : $default;

		// 设置默认值
		if(is_null($val)){
			$val = 0;
		}

		return (float) $val;
	}
}