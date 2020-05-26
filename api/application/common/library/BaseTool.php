<?php
namespace app\common\library;

use \app\common\library\RuntimeError;
use think\Validate;

class BaseTool{

	/**
	 * 逻辑删除项目
	 *
	 * @param Class    	$model 模型类实例
	 * @param String	$name  数据名称
	 */
	public static function deletedItemLogically($model, $name){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$deleted = absint(input('operate'));
		$operation_type = $deleted ? '删除' : '恢复';

		$result = $model->deletedItemLogically($id, $deleted);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => $result->getError()
			];
		}

		$msg = $operation_type.$result.'条'.$name.'记录';

		// 返回数据
		return [
			'status' => 1,
			'type'   => $operation_type,
			'result' => $result,
			'msg'    => $msg,
		];
	}

	/**
	 * 逻辑删除项目
	 *
	 * @param Class    	$model 模型类实例
	 * @param String	$name  数据名称
	 *
	 */
	public static function disableItem($model, $name){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$disabled = absint(input('operate'));
		$operation_type = $disabled ? '禁用' : '启用';

		$result = $model->disableItem($id, $disabled, ['is_admin' => 0]);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => $result->getError()
			];
		}

		// 返回数据
		$msg = $operation_type.$result.'条'.$name.'记录';
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
	public static function sortItem($model, $name){
		$data   = input('data/a');
		$result = $model->sortItem($data);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => $result->getError()
			];
		}

		// 返回数据
		$msg = '对'.$result.'条'.$name.'记录进行排序';
		return [
			'status' => 1,
			'result' => $result,
			'msg'    => $msg,
		];
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

			$args[$key] = self::input($name, $type, $default, $params);
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
			case 'string':{
				// 字符串
				$default == null && $default = '';
				$value = isset($data[$name]) ? htmlspecialchars(urldecode(strip_tags($data[$name])), ENT_QUOTES, 'UTF-8') : $default;
				break;
			}
			case 'int':{
				// 数字
				$default == null && $default = 0;
				$value = isset($data[$name]) ? absint($data[$name]) : $default;
				break;
			}
			case 'price':{
				// 价格
				$default == null && $default = 0;
				$value = isset($data[$name]) ? sprintf("%.2f",$data[$name]) : $default;
				break;
			}
			case 'float':{
				// 浮点数
				$default == null && $default = 0;
				$value = isset($data[$name])  ? filter_var($value, FILTER_VALIDATE_FLOAT) : $default;
				is_bool($value) && $value = $default;
				break;
			}
			case 'bool':{
				// 布尔值
				$default == null && $default = 0;
				$value = !empty($data[$name]) ? 1 : 0;
				break;
			}
			case 'array':{
				// 数组
				$default == null && $default = [];
				$value = isset($data[$name]) ? $data[$name] : [];
				is_array($value) || $value = [];
				break;
			}
			case 'time':{
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
	 * 获取数据
	 */
	public static function getData($model, $params){
		$id = isset($params['id']) ? absint($params['id']) : 0;

		if($id){
			return $model->getById($id);
		}

		return false;
	}

	/**
	 * 保存数据
	 */
	public static function saveData($model, $data){
		$data['update_time'] = time();
		if($model->isEmpty()){
			$data['add_time'] = $data['update_time'];
		}

		unset($data['roles']);
		return $model->data($data)->save();
	}

	/**
	 * 分页查询数据
	 */
	public static function page($model, $params){
		$page  = max(input('page/d'), 1);
		$where = isset($params['where']) 		? $params['where'] 		: [];
		$hidden  = isset($params['hidden']) 	? $params['hidden'] 	: [];
		$append  = isset($params['append']) 	? $params['append'] 	: [];
		$visible = isset($params['visible']) 	? $params['visible'] 	: [];
		$order 	 = isset($params['order']) 		? $params['order'] 		: [];
		$num   	 = isset($params['num']) 		? $params['num'] 		: 10;

		$data = $model
			->where($where)
			->hidden($hidden)		// 隐藏字段
			->append($append)		// 追加额外字段
			->visible($visible)		// 只显示传入的字段
			->page($page, $num)
			->order($order)
			->select();

		$total = $model->where($where)->count();

		return [
			'data' 	 => $data->toArray(),
			'num' 	 => $num,
			'total'  => $total,
		];
	}
}