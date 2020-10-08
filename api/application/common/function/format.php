<?php
/** 
 * 自定义公共函数库
 * 
 * 此函数文件中包含常用数据检验与格式化方法
 */

//----------------------------------------------------------------------

/**
 * 人名格式验证
 *
 * 2-4位中文 或 2-8字英文
 *
 * @param string $name, 被检查的人名
 *
 * @return bool
 */
function is_name($name){
	if(!is_scalar($name)){
		return false;
	}

	return preg_match('/^([\x{4e00}-\x{9fa5}]{2,4}|[A-Za-z]{2,8})$/u',$name) ? true : false;
}