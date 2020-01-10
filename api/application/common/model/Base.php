<?php
namespace app\common\model;

use think\Model;
use \app\common\library\RuntimeError;

class Base extends Model
{
	/**
	 * 禁用项目
	 *
	 * @param mixed		$id,		项目ID
	 * @param int		$delete,	0启用；1禁用
	 */
	public static function disableItem($id, $disable){
		$db = null;
		if(is_array($id)){
			$id = array_filter(array_map('absint', $id));

			$db = self::where('id', 'in', $id);
		}else{
			$id = absint($id);

			$db = self::where('id', $id);
		}

		if(empty($id)){
			return new RuntimeError('没有要操作的项目');
		}

		$update = $db->update(array(
			'is_disabled' => $disable,
			'update_time' => time(),
		));

		return $update;
	}

	/**
	 * 逻辑删除项目
	 *
	 * @param mixed		$id,			项目ID
	 * @param int		$delete,		0恢复；1删除
	 * @param int		$where,			软删除时附加条件
	 * @param mixed 	$attach_data	软删除时附加修改数据
	 */
	public static function deletedItemLogically($id, $deleted, $where = array(), $attach_data = array()){
		$db = null;
		$args = array(
			'is_deleted' => $deleted,
			'update_time' => time()
		);

		if(is_array($id)){
			$id = array_filter(array_map('absint', $id));

			$db = self::where('id', 'in', $id);
		}else{
			$id = absint($id);

			$db = self::where('id', $id);
		}

		if(empty($id)){
			return new RuntimeError('没有要操作的项目');
		}

		if(!empty($where)){
			$db -> where($where);
		}

		if(!empty($attach_data)){
			$args = array_merge($args, $attach_data);
		}

		$update = $db->update($args);

		return $update;
	}

	/**
	 * 项目自定义排序
	 */
	public static function orderItem($id, $num){
		$count = 0;

		if(empty($id) || empty($num)){
			return new RuntimeError('排序数据异常');
		}

		if(!is_array($id)){
			$id = array($id);
		}

		if(!is_array($num)){
			$num = array($num);
		}

		$id = array_filter(array_map('absint', $id));
		$num = array_map('absint', $num);

		if(empty($id)){
			return new RuntimeError('没有要操作的项目');
		}


		foreach($id as $k => $i){
			if(!$i || !isset($num[$k])) continue;

			$sort = $num[$k];
			$sort < 1 && $sort = 99;

			$update = self::where('id', $i)->update(['sort' => $sort]);

			$update !== false && $count += 1;
		}

		return $count;
	}

	/**
	 * 审核项目
	 *
	 * @param mixed		$id,			项目ID
	 * @param int		$delete,		0恢复；1删除
	 * @param int		$where,			审核时附加条件
	 */
	public static function verifyItem($id, $audit, $where = array(), $fail_reason = ''){
		$db = null;
		$args = array(
			'status' => $audit,
			'update_time' => time(),
			'fail_reason' => $fail_reason
		);

		if(is_array($id)){
			$id = array_filter(array_map('absint', $id));

			$db = self::where('id', 'in', $id);
		}else{
			$id = absint($id);

			$db = self::where('id', $id);
		}

		if(empty($id)){
			return new RuntimeError('没有要操作的项目');
		}

		if(!empty($where)){
			$db -> where($where);
		}

		$update = $db->update($args);

		return $update;
	}

	/**
	 * 根据id获取数据
	 */
	public static function getById($id, $field='*'){
		return self::where('is_deleted', 0)->where('is_disabled', 0)->field($field)->find($id);
	}
}