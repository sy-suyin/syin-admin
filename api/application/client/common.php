<?php

/**
 * 返回失败消息
 *
 * @param string	$msg,	消息
 * @param int		$code,	状态码
 *
 */
function show_error($msg, $code = 200){
	return json(array(
		'status' => 0,
		'msg'	 => $msg
	), $code);
}

/**
 * 返回成功消息
 *
 * @param string	$msg,	消息
 * @param mixed		$data,	要输出的数据
 */
function show_success($msg, $data=[]){
	return json(array(
		'status' => 1,
		'msg'	 => $msg,
		'result' => $data
	));
}

//----------------------------------------------------------------------

/**
 * ip2long函数修正
 * 修正ip2long函数在特定系统环境下会产生负值的问题
 *
 * @param string $ip, ip地址
 * @return string
 */
function ip_to_long($ip){
	return sprintf('%u', ip2long($ip));
}

//----------------------------------------------------------------------

/**
 * 获取指定分类的所有子分类
 *
 * @param int		$pid,		指定需要获取子分类的父ID，特殊的设置为0将获取所有分类
 * @param array		$category,	提前查询到的所有分类
 * @param string	$field,		指定返回字段值
 *
 * @return array
 */
function get_cate_children($pid, $category, $field=''){
	$children = array();

	foreach($category as $k => $cate){
		if($cate['parent_id'] != $pid)
			continue;

		$children[] = (!empty($field) && isset($cate[$field])) ? $cate[$field] : $cate;

		unset($category[$k]);

		$tmp = get_cate_children($cate['id'], $category, $field);

		if(!empty($tmp))
			$children = array_merge($children, $tmp);
	}

	return $children;
}

/**
 * 生成用户密码HASH
 *
 * @param string	$str,		明文密码
 * @param int		$count,		HASH计算次数
 * @param string	$algo,		用来在散列密码时指示算法的密码算法常量
 *
 * @return string
 */
function generate_password_hash($str, $count=10, $algo=PASSWORD_DEFAULT){
	$options = [
		'cost' => $count,
	];

	return password_hash($str, $algo, $options);
}

//----------------------------------------------------------------------

/**
 * 检验由generate_password_hash方法生成的用户密码HASH
 *
 * @param string	$str,		明文密码
 * @param int		$hash,		HASH值
 *
 * @return bool
 */
function check_password_hash($str, $hash){
	return password_verify($str, $hash);
}