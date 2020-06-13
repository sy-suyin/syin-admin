<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\DbTool;
use app\common\library\Input;

class SystemService extends BaseService {

	use DbTool;
	use Input;

	/**
	 * 获取管理员列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function adminListParams($params, $is_deleted = false){
		$keyword = self::stringFilter('keyword', $params, '', 't');
		$num = self::numberFilter('num', $params, 0);
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
		$keyword = self::stringFilter('keyword', $params, '', 't');
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
		}else{
			unset($data['password']);
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
	 * 角色禁止名单数据检查
	 */
	public static function roleBlocklistCheck($params){
		$blocklist = self::input('blocklist', 'array', $params, null);

		if(empty($blocklist)){
			return [
				'data' => [],
				'page' => [],
			];
		}

		$blocklist_data = self::input('data', 'array', $blocklist, null);
		$blocklist_page = self::input('page', 'array', $blocklist, null);

		// 处理数据权限
		if(!empty($blocklist_data)){
			$blocklist_data = self::roleBlocklistRepair($blocklist_data, 1);
		}

		// 处理页面权限
		if(!empty($blocklist_page)){
			$blocklist_page = self::roleBlocklistRepair($blocklist_page, 2);
		}

		return [
			'data' => $blocklist_data,
			'page' => $blocklist_page,
		];
	}

	/**
	 * 角色禁止名单数据转换
	 */
	protected static function roleBlocklistRepair($data, $type){
		$temp = [];
		$module = request()->module();

		foreach($data as $controller => $actions){
			if($controller != ''){
				$set = []; // 判断是否存在相同名的控制器

				foreach($actions as $action){
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
	 * 角色权限禁止名单数据保存
	 */
	public static function roleBlocklistSave($role, $data, $is_edit = false){
		$blocklist = [];
		$count = 0;

		$blocklist_data_edit = $is_edit ? !empty($_POST['blocklist_data_edit']) : true;
		$blocklist_page_edit = $is_edit ? !empty($_POST['blocklist_page_edit']) : true;

		if($blocklist_data_edit && !empty($data['data'])){
			$count += 1;
			foreach($data['data'] as $val){
				$val['role_id'] = $role['id'];
				$blocklist[] = $val;
			}
		}

		if($blocklist_page_edit && !empty($data['page'])){
			$count += 10;
			foreach($data['page'] as $val){
				$val['role_id'] = $role['id'];
				$blocklist[] = $val;
			}
		}

		if(empty($blocklist)){
			return true;
		}

		// 直接删除旧数据, 再重新插入新数据
		if($is_edit){
			if($count == 1){
				db('admin_role_blocklist')->where('role_id', $role['id'])->where('type', 1)->delete();
			}else if($count == 10){
				db('admin_role_blocklist')->where('role_id', $role['id'])->where('type', 2)->delete();
			}else{
				db('admin_role_blocklist')->where('role_id', $role['id'])->delete();
			}
		}

		return db('admin_role_blocklist')->insertAll($blocklist);
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