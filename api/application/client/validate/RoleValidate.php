<?php

/** 
 * 角色表单验证器
 */

namespace app\client\validate;
use app\common\validate\BaseValidate;

class RoleValidate extends BaseValidate{

    protected $rule = [
        'name'   =>  'require|unique:admin_role',
    ];

    protected $message = [
		'name.require' 	 => '请先输入角色名称',
        'name.unique' 	 => '名称已被占用',
    ];
}