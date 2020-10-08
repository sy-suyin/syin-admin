<?php
namespace app\common\library;

use app\common\library\RuntimeError;
use syin\Repository;
use think\Validate;
use syin\Input;

class BaseService{

	/**
	 * 执行搜索时的查询字段, 多个以 | 分隔
	 */
	protected static $search_fields = 'name';

	/**
	 * 各绑定简写对照
	 */
	protected static $bind_contrast = [
		's' => 'string',
		'd' => 'int',
		'a' => 'array',
		'b' => 'bool',
		'f' => 'float',
		'ff'=> 'file',
	];

	/**
	 * 最基础的分页参数获取
	 * 
	 * @param object 	$params			数据数组
 	 * @param bool 		$is_deleted		是否查询被删除的数据
 	 * @param object 	$where			查询条件
 	 * @param object 	$search			搜索条件, 将搜索和查询分开或许会更好
	 */
	public static function getListParams($params, $is_deleted = false, $where = [], $search = []){
		$page 	= isset($params['page']) ? $params['page'] : 1;
		$number = isset($params['num'])  ? $params['num']  : 10;
		$order  = 'id desc';
		$where['is_deleted'] = $is_deleted ? 1 : 0;

		// 未传入搜索数据时
		if(empty($search)){
			$keyword = isset($params['key']) ? $params['key'] : '';
			$fields = self::$search_fields;

			$search[$fields] = $keyword; 
		}

		// 此处需考虑搜索功能
		return [
			'page'	 => $page,
			'num' 	 => $number,
			'order'	 => $order,
			'search' => $search,
			'where'	 => $where,
		];
	}

	/**
	 * 获取数据库记录并检查
	 */
	public static function getRecord(Repository $repository, $params = null, $key = 'id'){
		is_null($params) && $params = $_GET;

		if(!empty($params) && isset($params[$key])){
			$val = $params[$key];
			$key == 'id' && $val = absint($val);
		}

		if(!empty($val)){
			$result = $repository->findBy($key, $val);
		}

		if(empty($result)){
			throw new RuntimeError('未找到相关数据');
		}

		return $result;
	}

	/**
	 * 检查参数
	 */
	public static function checkParams($params, $fields, $validation = false) {
		// 数据筛选过滤
		$args = self::filterParmas($fields, $params);

		// 验证参数
		if($validation) {
			$valid = self::validate($args, $validation['rules'], $validation['msgs']);
		}

		return $args;
	}

	/**
	 * 数据筛选过滤
	 */
	public static function filterParmas($fields, $params = []){
		$instance = Input::getInstance();
		$instance->bind($params);
		$args = [];

		// 获取参数
		foreach($fields as $field => $options){
			if(is_numeric($field)){
				$field = $options;
				$options = [];
			}

			if(is_string($options)){
				$options = [
					'name' => $options
				];
			}

			// 获取处理类型
			if(isset($options['type'])){
				$type = $options['type'];
			}else{
				$type = '';
				if (strpos($field, '/')) {
					list($field, $type) = explode('/', $field);
				}

				$type = isset(self::$bind_contrast[$type]) ? self::$bind_contrast[$type] : 'empty';
			}

			$name 	 = isset($options['name'])	  ? $options['name'] 	: $field;
			$default = isset($options['default']) ? $options['default'] : null;
			$filter  = isset($options['filter'])  ? $options['filter']  : null;
			$args[$name] = $instance->obtain($field, $type, $default, $filter);
		}

		return $args;
	}

	/**
	 * 验证输入数据
	 */
	public static function validate($params, $rules, $msgs) {
		// 进行数据验证
		if(empty($rules)){
			return true;
		}

		$validate = Validate::make($rules, $msgs);
		$valid = $validate->check($params);

		if(! $valid){
			throw new RuntimeError($validate->getError());
		}

		return true;
	}
}