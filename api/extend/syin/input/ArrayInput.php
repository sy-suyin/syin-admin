<?php

namespace syin\input;

class ArrayInput {

	public function execute($field, $params, $default = null){
		$val = isset($params[$field]) ? (array) $params[$field] : $default;

		if(! is_array($val)) {
			$val = $default;
		}

		// 设置默认值
		if(is_null($val)){
			$val = [];
		}
		
		return $val;
	}

	/**
	 * 将一维数组内数据全转换为整数
	 */
	public function int($val){
		$val = array_map('intval', $val);
		return $val;
	}

	/**
	 * 将一维数组内数据全转换为安全的字符串
	 */
	public function string($val){
		foreach($val as $k => $v){
			$val[$k] =  htmlspecialchars(urldecode(strip_tags($v)));
		}

		return $val;
	}

}