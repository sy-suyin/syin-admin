<?php
/** 
 * 自定义公共函数库
 * 
 * 此函数文件中包含常用数据检验与格式化方法
 */

//----------------------------------------------------------------------

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
 * 字符串过滤
 * 
 * @param string $str	需要处理的字符串
 * 
 * @return string
 */
function filter($str){
	return htmlspecialchars(strip_tags($str), ENT_COMPAT, 'UTF-8');
}

//----------------------------------------------------------------------

/**
 * 检查用户手机号码
 *
 * @param string $mobile, 需要验证的手机号码
 *
 * @return bool
 */
function is_mobile($mobile){
	if(empty($mobile)){
		return false;
	}

	return preg_match(''
		. '/^('
		. '((13([0-9]{1})|14([579])|15([012356789])|16([6])|17([0135678])|18([0-9]{1})|19([89]))'
		. '([0-9]{8}))|((170([059]))([0-9]{7}))'
		. ')$/', $mobile
	) ? true : false;
}

//----------------------------------------------------------------------

/**
 * 银行卡格式验证
 *
 * 借记卡（储蓄卡）/信用卡（贷记卡）通用 ，有效位数16-19位
 *
 * @param string $str, 被检查的字符
 *
 * @return bool
 */
function is_bank_card_number($str){
	if(!is_scalar($str))
		return false;

	$str = (string)$str;

	// 基础格式验证
	if(!preg_match('/^[0-9]{16,19}$/i', $str))
		return false;

	$basecode	= substr($str, 0, strlen($str) - 1);
	$verifycode	= substr($str, -1);
	$codelen	= strlen($basecode);
	$sum		= 0;

	// LUHN算法验证尾数
	for($i = 1; $i <= $codelen; $i++){
		if($i % 2 != 0){
			$num = (int)$basecode{$codelen - $i} * 2;
			$num = sprintf('%02d', $num);
			$sum += (int)$num{0} + (int)$num{1};
		}
		else
			$sum += (int)$basecode{$codelen - $i};
	}

	$mod		= $sum % 10;
	$checkcode	= ($mod == 0 ? 0 : 10 - $mod);

	if((int)$verifycode !== (int)$checkcode)
		return false;

	return true;
}

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

/**
 * 对价格进行格式化处理
 *
 * @param string	$price,		要格式化的价格
 */
function price($price){
	if(!is_scalar($price)){
		return 0;
	}

	return sprintf("%.2f", $price);
}