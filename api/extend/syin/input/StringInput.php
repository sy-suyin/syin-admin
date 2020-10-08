<?php

namespace syin\input;

class StringInput {

	public function execute($field, $params, $default = null){
		$val = isset($params[$field]) ? htmlspecialchars(urldecode(strip_tags($params[$field]))) : $default;

		// 设置默认值
		if(is_null($val)){
			$val = '';
		}

		return trim($val);
	}

	/**
	 * 过滤器
	 */
	public function time($val){
		return $val ? strtotime($val) : 0;
	}
}