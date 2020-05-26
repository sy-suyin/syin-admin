<?php
namespace app\common\model;

trait Validation{

	/**
	 * 获取数据字段
	 */
	public function getFields($scene = 'default'){
		$fields = $this->generateFields();

		if(isset($fields[$scene])){
			return $fields[$scene];
		}else if($scene != 'default' && isset($fields['default'])){
			return $fields['default'];
		}else{
			return false;
		}
	}

	/**
	 * 获取验证规则
	 */
	public function getRules($scene = 'default'){
		$validation = $this->generateValidation();

		$rules  = null;
		$msgs   = null;

		if(isset($validation['rules'][$scene])){
			$rules = $validation['rules'][$scene];
		}else if($scene != 'default' && isset($validation['rules']['default'])){
			$rules = $validation['rules']['default'];
		}else{
			return false;
		}

		if(isset($validation['msgs'][$scene])){
			$msgs = $validation['msgs'][$scene];
		}else if($scene != 'default' && isset($validation['msgs']['default'])){
			$msgs = $validation['msgs']['default'];
		}

		return [$rules, $msgs];
	}

}