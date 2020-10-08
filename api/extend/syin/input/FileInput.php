<?php

namespace syin\input;

class FileInput {

	public function execute($field, $params, $default = null){
		$val = isset($params[$field]) ? intval($params[$field]) : $default;

		return $val;
	}
}