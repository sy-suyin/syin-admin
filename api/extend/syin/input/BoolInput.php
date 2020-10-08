<?php

namespace syin\input;

class BoolInput {

	public function execute($field, $params){
		$val = !empty($params[$field]) ? 1 : 0;

		return $val;
	}
}