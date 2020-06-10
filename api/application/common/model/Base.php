<?php
namespace app\common\model;

use think\Model;
use \app\common\library\RuntimeError;

class Base extends Model{

	/**
	 * 查询范围 未被禁用的数据
	 */
    public function scopeNoDisabled($query){
		$query->where('is_disabled', 0);
    }

	/**
	 * 查询范围 未被删除的数据
	 */
    public function scopeNoDeleted($query){
		$query->where('is_deleted', 0);
	}

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
	 * 传入数据示例 [ {id: 1, sort: 5}, {id: 2, sort: 12} ]
	 *
	 * @param array $data	排序数据
	 *
	 */
	public static function sortItem($data){
		$count = 0;

		if(empty($data) || !is_array($data)){
			return new RuntimeError('没有要操作的项目');
		}

		foreach($data as $val){
			$id = absint($val['id']);
			$sort = absint($val['sort']);
			$sort < 1 && $sort = 99;

			if($id < 1){
				continue;
			}

			$update = self::where('id', $id)->update(['sort' => $sort]);
			if($update !== false){
				$count += 1;
			}
		}

		return $count;
	}

	/**
	 * 根据id获取数据
	 */
	public static function getById($id, $field='*'){
		return self::scope('nodeleted')->field($field)->find($id);
	}
}