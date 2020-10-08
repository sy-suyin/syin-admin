<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\Input;

class DictService extends BaseService {

	/**
	 * 获取字典列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function dictListParams($params, $is_deleted = false){
		self::getListParams($params, $is_deleted);
	}

	/**
	 * 获取字典内容列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function dictDataListParams($params){
		$where['dict_id'] = obtain('id/d', 0, '', $params);
		$params = self::getListParams($params, false, $where);
		return $params;
	}
}