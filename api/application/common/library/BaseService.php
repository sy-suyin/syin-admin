<?php
namespace app\common\library;

use \app\common\library\RuntimeError;
use think\db\Where;

class BaseService{

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

		if(!empty($where) && !($where instanceof Where)){
			$where = new Where($where);
		}

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