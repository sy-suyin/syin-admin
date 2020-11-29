<?php

//----------------------------------------------------------------------

/**
 * 调试函数
 *
 * @param mixed	$data,	要输出的数据
 */
function p($data){
	if(!headers_sent()){
		header("Content-type: text/html; charset=utf-8");
	}

	// 定义样式
	$str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
	// 如果是boolean或者null直接显示文字；否则print
	if (is_bool($data)) {
		$show_data=$data ? 'true' : 'false';
	}elseif (is_null($data)) {
		$show_data='null';
	}else{
		$show_data=print_r($data,true);
	}
	$str.=$show_data;
	$str.='</pre>';
	echo $str;
}

//----------------------------------------------------------------------

/**
 * 判断是否为自定义错误
 *
 * @param mixed $var, 需要判断的变量，一般为调用程序的返回值
 *
 * @return bool
 */
function is_error($var){
	if(!is_object($var))
		return false;

	return $var instanceof \app\common\library\RuntimeError;
}

/**
 * 强制转换为正整数
 *
 * @param mixed $value,	需要转换的数值
 *
 * @return int
 */
function absint($value){
	if(!is_scalar($value))
		return 0;

	return abs((int)$value);
}


/**
 * 获取参数
 * 当$name不为字符串时, 方法将根据第一个参数设置后续获取数据时, 所需用到的数据集
 * 
 * @param string|array $name 	数据键
 * @param mixed 	   $default 默认数据,
 * @param string|array $filter 	过滤方法, 多个过滤方法可以使用逗号分隔或者传入数组
 * @param array 	   $params	获取参数时所需的数据集, 默认为 $_POST
 */
function obtain($name = '', $default = null, $filter = '', $params = null){
	if(! is_string($name)){
		return \syin\Input::getInstance()->bind($name);
	}else{
		return \syin\Input::get($name, $default, $filter, $params);
	}
}

/**
 * 快速返回数据仓库实例
 */
function repository($name){
	$namespace = '\app\client\repository\\'.$name.'Repository';
	return new $namespace;
}