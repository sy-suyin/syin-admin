<?php

namespace syin\input;

class EmptyInput {

	public function execute($field, $params, $default = null){
		$val = isset($params[$field]) ? $params[$field] : $default;

		return $val;
	}
}