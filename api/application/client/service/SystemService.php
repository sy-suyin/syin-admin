<?php
namespace app\client\service;

use app\common\library\BaseTool;

class SystemService extends BaseTool {

	/**
	 * 获取管理员列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function adminListParams($params, $is_deleted = false){
		$keyword = isset($params['keyword'])  	? 	urldecode($params['keyword']) 	: '';
		$num 	 = isset($params['num']) 		? 	absint($params['num']) 			: 0;
		$num     = $num ?: config('common.page_num');
		$order  = ['sort' => 'asc', 'id' => 'desc'];
		$hidden = ['password'];
		$where  = [
			'is_deleted' => $is_deleted ? 1 : 0
		];

		if($keyword){
			$where[] = ['name|login', 'like', '%'.$keyword.'%'];
		}

		return [
			'num'	=> $num,
			'where' => $where,
			'order' => $order,
			'hidden'=> $hidden
		];
	}

	/**
	 * 获取角色列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function roleListParams($params, $is_deleted = false){
		$keyword = isset($params['keyword']) ? urldecode($params['keyword']) : '';
		$order = ['id' => 'desc'];
		$where = [
			'is_deleted' => $is_deleted ? 1 : 0
		];

		if($keyword){
			$where[] = ['name', 'like', '%'.$keyword.'%'];
		}

		return [
			'where' => $where,
			'order' => $order
		];
	}

	/**
	 * 管理员数据保存
	 */
	public static function adminSave($model, $data){
		$data['update_time'] = time();
		if($model -> isEmpty()){
			$data['add_time'] = $data['update_time'];
			$data['avatar'] = '/static/common/imgs/avatar/'.mt_rand(1,110).'.png';
		}

		if(!empty($data['password'])){
			$data['password'] = generate_password_hash($data['password']);
		}

		unset($data['roles']);
		return $model->data($data)->save();
	}

	/**
	 * 获取多个管理员关联角色信息
	 *
	 * @param array $data 		 需要进行转换的数据
	 */
	public static function adminMultiRelationRoles($data){
		$ids = [];
		$role_ids = [];

		// 1. 先获取管理员id
		foreach($data as $key => $val){
			$ids[] = $val['id'];
			$data[$key]['roles'] = [];
		}

		$relations = db('admin_role_relation')->where('admin_id', 'in', $ids)->select();

		// 2. 获取对应角色id
		$mapping = [];
		foreach($relations as $key => $val){
			if(!isset($mapping[$val['admin_id']])){
				$mapping[$val['admin_id']] = [];
			}

			$mapping[$val['admin_id']][] = $val['role_id'];
			$role_ids[$val['role_id']] = 1;
		}

		// 3. 获取角色信息, 并赋给管理, 角色信息可以缓存
		$role_ids = array_keys($role_ids);
		$roles = db('admin_role')->where('id', 'IN', $role_ids)->column('id, name');

		if(!empty($roles)){
			foreach($data as $key => $val){
				if(isset($mapping[$val['id']])){
					foreach($mapping[$val['id']] as $rid){
						if(isset($roles[$rid])){
							$data[$key]['roles'][] = [
								'id'   => $rid,
								'name' => $roles[$rid]
							];
						}
					}
				}
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
			// 先接触绑定,再建立新的绑定
			db('admin_role_relation')->where('admin_id', $admin->id)->delete();
		}

		return db('admin_role_relation')->insertAll($relation);
	}

	/**
	 * 角色禁止权限数据检查
	 */
	public static function roleForbidCheck($params){
		$data_forbid = self::input('data_forbid', 'array', null, $params);
		$page_forbid = self::input('page_forbid', 'array', null, $params);

		// 处理数据权限
		if(!empty($data_forbid)){
			$data_forbid = self::forbidDataRepair($data_forbid, 1);
		}

		// 处理页面权限
		if(!empty($page_forbid)){
			$page_forbid = self::forbidDataRepair($page_forbid, 2);
		}

		return [
			'data_forbid' => $data_forbid,
			'page_forbid' => $page_forbid,
		];
	}

	/**
	 * 角色禁止权限数据转换
	 */
	protected static function forbidDataRepair($data, $type){
		$temp = [];
		$module = request()->module();

		foreach($data as $controller => $actions){
			// $controller = self::stringFilter($controller, '');

			if($controller != ''){
				$set = []; // 判断是否存在相同名的控制器

				foreach($actions as $action){
					// $action = self::stringFilter($action, '');

					if($action != '' && !isset($set[$action])){
						$set[$action] = 1;
						$temp[] = [
							'module' 	 => $module,
							'controller' => $controller,
							'action' 	 => $action,
							'type'		 => $type
						];
					}
				}

				unset($set);
			}
		}

		return $temp;
	}

	/**
	 * 角色权限禁止信息保存
	 */
	public static function roleForbidSave($role, $data, $is_edit = false){
		$bans = [];
		$count = 0;

		$data_forbid_edit = $is_edit ? !empty($_POST['data_forbid_edit']) : true;
		$page_forbid_edit = $is_edit ? !empty($_POST['page_forbid_edit']) : true;

		if($data_forbid_edit && !empty($data['data_forbid'])){
			$count += 1;
			foreach($data['data_forbid'] as $val){
				$val['role_id'] = $role['id'];
				$bans[] = $val;
			}
		}

		if($page_forbid_edit && !empty($data['page_forbid'])){
			$count += 10;
			foreach($data['page_forbid'] as $val){
				$val['role_id'] = $role['id'];
				$bans[] = $val;
			}
		}

		if(empty($bans)){
			return true;
		}

		// 直接删除旧数据, 再重新插入新数据
		if($is_edit){
			if($count == 1){
				db('admin_role_ban')->where('role_id', $role['id'])->where('type', 1)->delete();
			}else if($count == 10){
				db('admin_role_ban')->where('role_id', $role['id'])->where('type', 2)->delete();
			}else{
				db('admin_role_ban')->where('role_id', $role['id'])->delete();
			}
		}


		return db('admin_role_ban')->insertAll($bans);
	}

	/**
	 * 获取角色的禁止权限信息
	 */
	public static function getRoleForbidData($id){
		$forbid_data = db('admin_role_ban')->where('role_id', $id)->select();
		$forbid = [
			'data_forbid' => [],
			'page_forbid' => [],
		];

		if(!empty($forbid_data)){
			foreach($forbid_data as $val){
				if($val['type'] == 1){
					$forbid['data_forbid'][] = $val;
				}elseif($val['type'] == 2){
					$forbid['page_forbid'][] = $val;
				}
			}
		}

		return $forbid;
	}
}