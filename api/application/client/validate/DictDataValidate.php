<?php
/** 
 * 管理员表单验证器
 */

namespace app\client\validate;
use app\common\validate\BaseValidate;

class DictDataValidate extends BaseValidate{
	protected $rule = [
        'data'  =>  'require|unique:dict_data',
    ];
    
    protected $message = [
        'data.require'  => '字典数据不能为空',
        'data.unique'   => '字典数据已存在,不可重复',
    ];
    
    protected $scene = [
    ];
}