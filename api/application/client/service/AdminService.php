<?php
namespace app\client\service;

use app\client\repository\RoleRepository;
use app\common\library\RuntimeError;
use app\common\library\BaseService;
use syin\Repository;

class AdminService extends BaseService {

	/**
	 * 获取管理员列表查询所需条件
	 *
	 */
	public static function adminListParams(){
		$data = $_POST;
		obtain($data);

		$where  = [];
		$time   = obtain('time/s', 0, 'time');
		$status = obtain('status/d');

		if($time){
			$where['add_time'] = ['>', $time];
		}

		if($status){
			$where['is_disabled'] = $status == 1 ? 1 : 0;
		}

		$params = self::getListParams($data, $where);
		$params['order'] = ['sort asc', 'id desc'];
		$params['hidden'] = ['password'];
		return $params;
	}

	/**
	 * 检查提交的数据
	 */
	public static function checkRequest($model = null) {
		$is_edit = is_null($model) ? false : true;
		$fields = [
			'name',
			'password',
			'login' 	 => 'login_name',
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

		// 数据筛选过滤
		$args = self::filterParmas($fields, $_POST);

		// 验证参数
		self::validate($args, $validation['rules'], $validation['msgs']);

		if(! $is_edit){
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
	 * 检查角色修改的信息
	 */
	public static function getProfileParams($admin){
		$fields = [
			'login' 	=> 'login_name',
			'name',
		];

		$rules = [
			'name|名称'      	    => 'require|unique:admin',
			'login_name|登录名称'   => 'require|alphaNum',
		];

		// 数据筛选过滤
		$args = self::filterParmas($fields, $_POST);

		// 验证参数
		$args['id'] = $admin->id;
		self::validate($args, $rules);

		$other = self::filterParmas([
			'password',
			'confirmpwd',
			'oldpwd',
			'avatar/d'
		]);

		// 此处检查密码并对密码进行加密
		if(!empty($args['password'])){
			if($other['password'] == $other['oldpwd']){
				throw new RuntimeError('新密码不能与旧密码一致');
			}

			if($other['password'] != $args['confirmpwd']){
				throw new RuntimeError('确认密码与新密码不一致');
			}

			if(generate_password_hash($args['oldpwd']) == $admin['password']){
				throw new RuntimeError('旧密码有误, 请检查后重试');
			}

			$args['password'] = generate_password_hash($args['password']);
		}

		return $args;
	}

	/**
	 * 获取多个管理员关联角色信息
	 *
	 * @param array $data 		 需要进行转换的数据
	 */
	public static function adminMultiRelationRoles(Repository $repository, $admins){
		// 延迟预加载
		$roleRepo  = new RoleRepository();
		$admins->load('Relation');
		$admins = $admins->toArray();
		$bucket = [];
		$role_ids = [];

		foreach($admins as $key => $admin){
			$admins[$key]['roles'] = [];
			foreach($admin['relation'] as $relation){
				$role_id = $relation['role_id'];
				$bucket[$role_id][] = $key;
				$role_ids[$role_id] = 1;
			}
		}

		$role_ids = array_keys($role_ids);
		$where['id'] = ['in', $role_ids];
		$roles = $roleRepo->select($where);

		foreach($roles as $role){
			foreach($bucket[$role['id']] as $admin_index){
				$admins[$admin_index]['roles'][] = $role;
			}
		}

		return $admins;
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
}