<?php
namespace app\common\library;

use \app\common\library\RuntimeError;

class BaseTool{

	// 参数类型 - 字符串
	const STRING_TYPE = 0;

	// 参数类型 - 数字
	const INT_TYPE = 1;

	// 参数类型 - 价格
	const PRICE_TYPE = 2;

	// 参数类型 - 浮点数
	const FLOAT_TYPE = 3;

	// 参数类型 - 布尔值
	const BOOL_TYPE = 4;

	// 参数类型 - 数组
	const ARRAY_TYPE = 5;

	// 参数类型 - 时间
	const TIME_TYPE = 6;

	// 参数类型 - 其他
	const OTHER_TYPE = 7;

	/** 
	 * 获取请求参数
	 * 
	 */
	public static function getRequestParams($fields){
		$args = array();

		if(!empty($fields)){
			foreach($fields as $key => $field){
				!is_array($field) && $field = array('type'=>$field);

				$name    = isset($field['name'])    ? 	$field['name'] 		: 	$key;
				$type    = isset($field['type']) 	? 	$field['type']		: 	self::STRING_TYPE;
				$default = isset($field['default']) ?	$field['default'] 	:	null;

				$args[$key] = self::input($name, $type, $default); 
			}
		}

		return $args;
	}

	/** 
	 * 过滤处理表单数据
	 */
	public static function input($name, $type, $default=null, $data=null){
		!empty($data) || $data = $_REQUEST;
		$value = '';
		switch($type){
			case self::STRING_TYPE:{
				// 字符串
				$default == null && $default = '';
				$value = self::stringFilter($data[$name], $default);
				break;
			}
			case self::INT_TYPE:{
				// 数字
				$default == null && $default = 0;
				$value = self::intFilter($data[$name], $default);
				break;
			}
			case self::PRICE_TYPE:{
				// 价格
				$default == null && $default = 0;
				$value = isset($data[$name]) ? sprintf("%.2f",$data[$name]) : $default;
				break;
			}
			case self::FLOAT_TYPE:{
				// 浮点数
				$default == null && $default = 0;
				$value = self::floatFilter($data[$name], $default);
				is_bool($value) && $value = $default;
				break;
			}
			case self::BOOL_TYPE:{
				// 布尔值
				$default == null && $default = 0;
				$value = !empty($data[$name]) ? 1 : 0;
				break;
			}
			case self::ARRAY_TYPE:{
				// 数组
				$default == null && $default = [];
				$value = isset($data[$name]) ? $data[$name] : [];
				is_array($value) || $value = [];

				// $value = array_map('filter', $value);
				break;
			}
			case self::TIME_TYPE:{
				// 时间
				$default == null && $default = 0;
				$value = isset($data[$name]) ? strtotime($data[$name]) : 0;
				break;
			}
			default:{
				$default == null && $default = '';
				$value = isset($data[$name]) ?	$data[$name]	:	$default;
			}
		}

		return $value;
	}

	/**
	 * 字符串过滤
	 */
	public static function stringFilter($value, $default=''){
		$value = isset($value) ? htmlspecialchars(urldecode(strip_tags($value)), ENT_QUOTES, 'UTF-8') : $default;
		return $value;
	} 

	/**
	 * 整数过滤
	 */
	public static function intFilter($value, $default=0){
		$value = isset($value) ? absint($value) : $default;
		return $value;
	}

	/**
	 * 浮点数过滤
	 */
	public static function floatFilter($value, $default=0){
		$value = isset($value)  ? filter_var($value, FILTER_VALIDATE_FLOAT) : $default;
		return $value;
	}

	/**
	 * 获取表单提交的数据
	 */
	public static function autoDeal(){
		$part = self::input('part', self::ARRAY_TYPE);
		$args = self::input('args', self::ARRAY_TYPE);

		if(empty($part) || empty($args)){
			return false;
		}

		$def_arr = [
			'txt'
		];

		foreach($part as $key => $type){
			$val = '';
			switch($type){
				case 'num': {
					$val = isset($args[$key]) ? absint($args[$key]) : 0;
					break;
				}
				default:{
					$val = isset($args[$key]) ? file ($args[$key]) : 0;
				}
			}

		}
	}

	/** 
	 * 清除缩略图
	 */
	public static function clearThumb(){

	}

	public static function saveImage(){

	}
}