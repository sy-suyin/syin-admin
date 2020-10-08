<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\DbTool;
use app\common\library\Input;

class SystemService extends BaseService {

	use DbTool;

	/**
	 * 获取角色列表查询所需条件
	 *
 	 * @param bool 	$params			数据数组
 	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function roleListParams($params, $is_deleted = false){
		return self::getListParams($params, $is_deleted);
	}

	/**
	 * 检查提交的数据
	 */
	public static function checkRequest($params, $model = null) {
		$is_edit = is_null($model) ? false : true;
		$fields = [
			'name',
			'login_name' => 'login',
			'password',
			'roles/a'	 => ['filter' => 'int'],
		];

		$validation = [
			'rules' => [
				'name'      	=> 'require|unique:admin',
				'login_name'   	=> 'require|alphaNum',
				'roles'			=> 'array|min:1'
			],
			'msgs'  => [
				'name.require' 	 => '请先输入名称',
				'name.unique' 	 => '名称已被占用',
				'login_name.require' => '请先输入登录账号名称',
				'login_name.alphaNum'=> '登录账号只能是字母和数字',
				'password.require'   => '登录密码不能为空',
				'roles.array'	 => '角色数据有误',
				'roles.min'	 	 => '请先选择角色',
			]
		];

		if(! $is_edit){
			$validation['rules']['password'] = 'require';
		}

		$args = self::checkParams($params, $fields, $validation);

		$args['update_time'] = time();
		if(! $is_edit){
			$args['add_time'] = $args['update_time'];
			$args['avatar']   = '/static/common/imgs/avatar/'.mt_rand(1,110).'.png';
		}
		
		// 此处检查密码并对密码进行加密
		if(!empty($args['password'])){
			$args['password'] = generate_password_hash($args['password']);
		}else{
			unset($args['password']);
		}

		return $args;
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

		if($blocklist_data_edit){
			$count += 1;

			if(!empty($data['data'])){
				foreach($data['data'] as $val){
					$val['role_id'] = $role['id'];
					$blocklist[] = $val;
				}
			}
		}

		if($blocklist_page_edit){ 
			$count += 10;

			if(!empty($data['page'])){
				foreach($data['page'] as $val){
					$val['role_id'] = $role['id'];
					$blocklist[] = $val;
				}
			}
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

		if(empty($blocklist)){
			return true;
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