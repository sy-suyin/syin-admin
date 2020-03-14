<?php
/** 
 * 管理员表单验证器
 */

namespace app\client\validate;
use app\common\validate\BaseValidate;

class AdminValidate extends BaseValidate{

    protected $rule = [
        'name'      	=>  'require|unique:admin',
        'login_name'   	=>  'require',
    ];

    protected $message = [
        'name.require' 	 => '请先输入名称',
        'name.unique' 	 => '名称已被占用',
		'login_name.require'  => '请先输入登录账号名称',
    ];
}