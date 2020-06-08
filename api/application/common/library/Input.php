<?php
namespace app\common\library;

use \app\common\library\RuntimeError;
use think\Validate;

trait Input{

	/**
	 * 对前端传入的方法进行检查
	 */
	public static function requestCheck($model, $params, $scene = false){
		$is_add = $scene == 'add' ? true : false;
		$id = 0;

		// 自动选择场景
		if($scene === false){
			$is_add = isset($params['id']) ? false : true;
			$scene = $is_add ? 'add' : 'edit';
		}

		if(! $is_add && isset($params['id'])){
			$id = absint($params['id']);

			if(! ($model = $model->getById($id))){
				return new RuntimeError('未找到相关数据');
			}
		}

		$fields = $model->getFields($scene);
		$validation = $model->getRules($scene);

		if(empty($fields)){
			return new RuntimeError('未找到相关字段配置');
		}

		// 对数据进行基础过滤
		$data = self::getRequestParams($fields, $params);

		// 给数据加上id
		$is_add || $data['id'] = $id;

		// 进行数据验证
		if(!empty($validation)){
			list($rules, $msgs) = $validation;

			$validate = Validate::make($rules, $msgs);
			$result = $validate->check($data);

			if(!$result){
				return new RuntimeError($validate->getError());
			}
		}

		return [$data, $model];
	}

	/**
	 * 对前端传入的二维数组进行检查
	 */
	public static function requestMultiCheck($model, $params, $scene = false){
		$is_edit = $scene == 'edit' ? true : false;

		// 自动选择场景
		if($scene === false){
			$is_edit = isset($params['id']) ? true : false;
			$scene = $is_edit ? 'edit' : 'add';
		}

		$fields = $model->getFields($scene);
		$validation = $model->getRules($scene);
		$results = [];

		if(empty($fields)){
			return new RuntimeError('未找到相关字段配置');
		}

		// 进行数据验证
		if(!empty($validation)){
			list($rules, $msgs) = $validation;

			$validate = Validate::make($rules, $msgs);
		}

		foreach($params as $param){
			// 对数据进行基础过滤
			$data = self::getRequestParams($fields, $param);

			// 进行数据验证
			if(!empty($validation)){
				$valid = $validate->check($data);

				if(!$valid){
					continue;
				}
			}

			$results[] = $data;
		}

		return [$data, $model];
	}

	/**
	 * 获取请求参数
	 *
	 */
	public static function getRequestParams($fields, $params){
		$args = [];

		foreach($fields as $key => $field){
			!is_array($field) && $field = array('type'=>$field);

			$name    = isset($field['name'])    ? 	$field['name'] 		: 	$key;
			$type    = isset($field['type']) 	? 	$field['type']		: 	'string';
			$default = isset($field['default']) ?	$field['default'] 	:	null;

			$args[$key] = self::input($name, $type, $params, $default);
		}

		return $args;
	}

	/**
	 * 过滤处理表单数据
	 */
	public static function input($name, $type, $params, $default=null){
		$func = $type.'Filter';
		$name = explode('/', $name);
		$name[] = '';

		$value = self::$func($name[0], $params, $default, $name[1]);
		return $value;
	}

	/**
	 * 字符串过滤
	 *
	 * @param string  $name		参数键
	 * @param array   $params	参数数组
	 * @param string  $default	默认值
	 * @param string  $filter	过滤器, t: 移除空格
	 *
	 * @return string
	 */
	public static function stringFilter($name, $params, $default = '', $filter = ''){
		$default == null && $default = '';
		$value = isset($params[$name]) ? htmlspecialchars(urldecode(strip_tags($params[$name])), ENT_QUOTES, 'UTF-8') : $default;

		if($value){
			if($filter == 't'){
				$value = trim($value);
			}
		}

		return $value;
	}

	/**
	 * 数字过滤, 默认为正整数
	 *
	 * @param string  $name		参数键
	 * @param array   $params	参数数组
	 * @param string  $default	默认值
	 * @param string  $filter	过滤器, f: 浮点数, p: 价格
	 */
	public static function numberFilter($name, $params, $default = 0, $filter = ''){
		$default == null && $default = 0;
		$value = isset($params[$name]) ? $params[$name] : $default;

		if(!is_scalar($value)){
			$value = 0;
		}

		if($value){
			switch($filter){
				case 'f':{
					// 浮点数
					$value = filter_var($value, FILTER_VALIDATE_FLOAT);
					break;
				}
				case 'p':{
					// 价格
					$value = sprintf("%.2f",$value) * 1;
					break;
				}
				default:{
					$value = abs((int)$value);
				}
			}
		}

		return $value;
	}

	/**
	 * 布尔值过滤
	 *
	 * @param string  $name		参数键
	 * @param array   $params	参数数组
	 * @param string  $default	默认值
	 * @param string  $filter	过滤器, d: 转正整数
	 * 
	 * @return bool
	 */
	public static function boolFilter($name, $params, $default = false, $filter = ''){
		$value = isset($params[$name]) ? $params[$name] : $default;

		if(is_string($value)){
			if($value == 'false'){
				$value = false;
			}else{
				$value = true;
			}
		}

		$value = !empty($value) ? true : false;

		if($filter == 'd'){
			$value = $value ? 1 : 0;
		}

		return $value;
	}

	/**
	 * 数组过滤
	 *
	 * @param string  $name		参数键
	 * @param array   $params	参数数组
	 * @param string  $default	默认值
	 * @param string  $filter	过滤器, 仅能处理一维数组, d: 转正整数
	 */
	public static function arrayFilter($name, $params, $default = [], $filter = ''){
		$default == null && $default = [];
		$value = isset ($params[$name]) ? $params[$name] : $default;

		if(! is_array($value)){
			return $default;
		}

		if(!empty($value)){
			switch($filter){
				case 'd': {
					$value = array_filter(array_map('absint', $value));
					break;
				}
			}
		}

		return $value;
	}

	/**
	 * 时间过滤
	 *
	 * @param string  $name		参数键
	 * @param array   $params	参数数组
	 * @param string  $default	默认值
	 * @param string  $filter	过滤器, s: 转时间戳
	 */
	public static function timeFilter($name, $params, $default = 0, $filter = ''){
		$default == null && $default = 0;
		$value = isset ($params[$name]) ? $params[$name] : $default;

		if($value){
			if($filter == 's'){
				$value = strtotime($value);
			}

			if(! $value){
				$value = $default;
			}
		}

		return $value;
	}
}