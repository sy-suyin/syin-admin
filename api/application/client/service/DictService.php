<?php
namespace app\client\service;

use app\common\library\BaseTool;

class DictService extends BaseTool {

	/**
	 * 获取角色列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function dictListParams($params, $is_deleted = false){
		$num   	= isset($params['num'])	? 	absint($params['num'])	: 0;
		$num   	= $num ?: config('common.page_num');
		$order 	= ['id' => 'desc'];
		$where 	= [
			'is_deleted' => $is_deleted ? 1 : 0
		];

		return [
			'num' 	=> $num,
			'where' => $where,
			'order' => $order
		];
	}

	/**
	 * 获取角色列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function dictDataListParams($params, $is_deleted = false){
		$num	= isset($params['num'])	? 	absint($params['num'])	: 0;
		$num	= $num ?: config('common.page_num');
		$id  	= isset($params['id']) ? absint($params['id']) : 0;
		$order 	= ['id' => 'desc'];
		$where 	= [
			'dict_id' => $id
		];

		return [
			'num' 	=> $num,
			'where' => $where,
			'order' => $order
		];
	}
}