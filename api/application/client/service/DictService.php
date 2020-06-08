<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\Input;

class DictService extends BaseService {

	use Input;

	/**
	 * 获取字典列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function dictListParams($params, $is_deleted = false){
		$num 	= self::numberFilter('num', $params, 0);
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
	 * 获取字典内容列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function dictDataListParams($params){
		$num 	= self::numberFilter('num', $params, 0);
		$id 	= self::numberFilter('id', $params, 0);
		$num	= $num ?: config('common.page_num');
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