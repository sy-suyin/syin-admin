<?php
namespace app\common\library;

use app\common\library\RuntimeError;
use syin\Repository;
use syin\Input;

class BaseService{

	/**
	 * 执行搜索时的查询字段, 多个以 | 分隔
	 */
	protected static $search_fields = 'name';

	/**
	 * 是否开启数据限制
	 */
	protected static $data_limit = false;

	/**
	 * 数据限制字段, 请务必保证该字段在数据库中真实存在
	 */
	protected static $data_limit_field = 'admin_id';

	/**
	 * 数据限制开启时自动填充限制字段值
	 */
	protected static $data_limit_auto_fill = false;

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
	public static function getListParams($params, $where = [], $search = []){
		$page 	= isset($params['page']) ? $params['page'] : 1;
		$number = isset($params['num'])  ? $params['num']  : 10;
		$order  = 'id desc';

		// 未传入搜索数据时
		if(empty($search)){
			$keyword = isset($params['key']) ? $params['key'] : '';
			$fields = self::$search_fields;

			$search[$fields] = $keyword;
		}

		// 数据限制
		self::$data_limit && $where[self::$data_limit_field] = request()->auth->id;

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

		// 数据限制
		if(self::$data_limit && $result[self::$data_limit_field] != request()->auth->id){
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
			self::validate($args, $validation['rules'], $validation['msgs']);
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

		// 数据自动填充
		self::$data_limit_auto_fill && $args[self::$data_limit_field] = request()->auth->id;

		// id补充
		if(!isset($args['id'])){
			$id =  $instance->obtain('id', 'int', 0);
			$id && $args['id'] = $id;
		}

		return $args;
	}

	/**
	 * 获取验证器实例
	 */
	public static function getValidate($rules, $msgs = []){
		return new \app\common\library\BaseValidate($rules, $msgs);
	}

	/**
	 * 验证输入数据
	 */
	public static function validate($params, $rules, $msgs = []) {
		// 进行数据验证
		if(empty($rules)){
			return true;
		}

		$validate = self::getValidate($rules, $msgs);
		$valid = $validate->check($params);

		if(! $valid){
			throw new RuntimeError($validate->getError());
		}

		return true;
	}

	/**
	 * 删除数据
	 */
	public static function delete(Repository $repository, $name, $where = []){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$deleted = absint(input('operate'));
		$operation_type = $deleted  ? '删除' : '恢复';
		$count = 0;

		if(empty($id)){
			return new RuntimeError('没有要操作的项目');
		}

		if(is_array($id)){
			$id = array_filter(array_map('absint', $id));

			$where['id'] = ['in', $id];
		}else{
			$where['id'] = absint($id);
		}

		// 数据限制
		self::$data_limit && $where[self::$data_limit_field] = request()->auth->id;

		$results = $repository->withDeleted(!$deleted)->select($where, ['id']);

		$func = $deleted ? 'delete' : 'restore';
		foreach($results as $result){
			$count += $result->$func();
		}

		$msg = $operation_type.$count.'条'.$name.'记录';

		// 返回数据
		return [
			'status' => 1,
			'type'   => $operation_type,
			'result' => $count,
			'msg'    => $msg,
		];
	}

	/**
	 * 表格批量操作
	 */
	public static function multi(Repository $repository, $field, $default = 0){
		$params = $_POST;
		$ids = isset($params['id']) ? $params['id'] : 0;
		$values = isset($params['data']) ? $params['data'] : 0;
		$count = 0;

		if(empty($ids)){
			throw new RuntimeError('未找到相关数据');
		}

		if(is_array($values)){
			if(empty($values)){
				throw new RuntimeError('未找到相关数据');
			}
		}else{
			$values = absint($values);
		}

		// 数据限制
		if(self::$data_limit){
			$repository->where([
				self::$data_limit_field => request()->auth->id
			]);
		}

		if(is_array($values)){
			foreach($ids as $k => $id){
				if(!isset($values[$k])){
					continue;
				}
				
				$val = isset($values[$k]) ? $values[$k] : $default;
				$count += $repository->update([
					$field => $val,
				], $id);
			}
		}else{
			foreach($ids as $id){
				$count += $repository->update([
					$field => $values,
				], $id);
			}
		}
		return $count;
	}

	/**
	 * 禁用/启用数据
	 * 禁用操作使用较多, 所以值得额外封装
	 *
	 * @param Class 	$repository		数据仓库实例
	 * @param String	$name			数据名称
	 */
	public static function disableItem(Repository $repository, $name){
		$data  = obtain('data/b', 0);
		$operation_type = $data ? '禁用' : '启用';
		$result = self::multi($repository, 'is_deleted', 0);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => '操作失败：'.$result->getError()
			];
		}

		$msg = '操作成功, 共'.$operation_type.$result.'条'.$name.'记录';
		return [
			'status' => 1,
			'type'   => $operation_type,
			'result' => $result,
			'msg'    => $msg,
		];
	}

	/**
	 * 项目自定义排序
	 *
	 * @param Class    	$model 模型类实例
	 * @param String	$name  数据名称
	 *
	 */
	public static function sortItem(Repository $repository, $name){
		$result = self::multi($repository, 'sort', 99);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => '操作失败：'.$result->getError()
			];
		}

		$msg = '操作成功, 共对'. $result. '条'.$name.'记录进行排序';
		return [
			'status' => 1,
			'result' => $result,
			'msg'    => $msg,
		];
	}
}