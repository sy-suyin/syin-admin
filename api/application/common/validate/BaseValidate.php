<?php

namespace app\common\validate;
use think\Validate;

class BaseValidate extends Validate{
	protected function isempty($type){
		if($type == 'int'){
			return 0 == absint($type) ? true : false;
		}elseif($type == 'string'){
			return '' == trim($type) ? true : false;
		}else{
			return empty($type) ? true : false;
		}
	}
}