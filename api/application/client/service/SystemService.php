<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\DbTool;
use app\common\library\Input;

class SystemService extends BaseService {

	/**
	 * 获取多个管理员关联角色信息
	 *
	 * @param array $data 		 需要进行转换的数据
	 */
	public static function adminMultiRelationRoles($data){
		$admin_ids = [];
		// 桶
		$barrel	   = [];

		// 取管理员id
		foreach($data as $key => $val){
			$admin_ids[$val['id']] = $key;
		}

		$roles = db('admin_role_relation')->where('admin_id', 'in', array_keys($admin_ids))->select();

		foreach($roles as $role){
			$barrel[$role['role_id']][] = $admin_ids[$role['admin_id']];
		}

		$roles = db('admin_role')->field('id, name, description')->where('id', 'in', array_keys($barrel))->select();
		foreach($roles as $role){
			foreach($barrel[$role['id']] as $admin_index){
				$data[$admin_index]['roles'][] = $role;
			}
		}

		return $data;
	}

	/**
	 * 获取单个管理员关联角色信息
	 *
	 * @param int $admin_id 	管理员id
	 */
	public static function adminRelationRoles($admin_id){
		$role_ids = [];

		if(is_array($admin_id)){
			$db = db('admin_role_relation')->where('admin_id', 'in', $admin_id);
		}else{
			$db = db('admin_role_relation')->where('admin_id', $admin_id);
		}

		$relations = $db->select();

		// 2. 获取对应角色id,
		foreach($relations as $val){
			$role_ids[$val['role_id']] = 1;
		}

		// 3. 获取角色信息, 并赋给管理, 角色信息可以缓存
		$role_ids = array_keys($role_ids);
		$roles = db('admin_role')
			->field('id, name, description')
			->where('is_deleted', 0)
			->where('is_disabled', 0)
			->where('id', 'IN', $role_ids)
			->select();

		return $roles;
	}

	/**
	 * 管理员关联角色信息保存
	 */
	public static function adminRoleSave($admin, $roles, $is_edit = false){
		// 添加时
		$relation = [];
		foreach($roles as $role){
			$relation[] = [
				'admin_id' => $admin->id,
				'role_id'  => $role,
			];
		}

		if($is_edit){
			// 先解除绑定,再建立新的绑定
			db('admin_role_relation')->where('admin_id', $admin->id)->delete();
		}

		return db('admin_role_relation')->insertAll($relation);
	}

	/**
	 * 获取角色的禁止名单信息
	 */
	public static function getRoleBlocklist($id){
		$results = db('admin_role_blocklist')->where('role_id', $id)->select();
		$blocklist = [
			'data' => [],
			'page' => [],
		];

		if(!empty($results)){
			foreach($results as $val){
				$type = $val['type'] == 1 ? 'data' : 'page';

				$blocklist[$type][] = $val;
			}
		}

		return $blocklist;
	}
}