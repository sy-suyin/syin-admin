<?php
namespace app\client\service;

use app\client\repository\AdminRepository;
use app\client\repository\RoleRepository;
use app\common\library\RuntimeError;
use syin\Repository;

class Auth {

	protected static $instance;

	protected $auth;

	protected $repository;

	protected $logined = false;

	public static function getInstance(){
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
	}

	/**
	 * 设置数据仓库
	 */
	public function setRepository(Repository $repository){
		$this->repository = $repository;
	}

	/**
	 * 获取数据
	 */
	public function __get($name){
		if(! $this->isLogin()){
			return false;
		}

		return isset($this->auth[$name]) ? $this->auth[$name] : null;
	}

	/**
	 * 登录
	 * 
	 * @param string $login 	登录账号
	 * @param string $password  登录密码
	 *
	 */
	public function login($login, $password){
		$auth = $this->repository->findBy('login_name', $login);

		if(empty($auth)){
			throw new RuntimeError('登录账号或密码错误');
		}

		// 登录密码
		if(! check_password_hash($password, $auth->password)){
			throw new RuntimeError('登录账号或密码错误');
		}

		if($auth->is_disabled){
			return new RuntimeError('登录账号已被禁用, 请联系管理');
		}

		$this->auth = $auth;
		$this->logined = true;
		return $auth;
	}

	/**
	 * 退出登录
	 */
	public function logout(){
	}

	/**
	 * 自动登录
	 */
	public function autologin($token){
		if($token == ''){
			return false;
		}

		$token = substr($token, 7);
		if(empty($token)){
			return false;
		}

		$payload = TokenService::verifyToken($token);
		if($payload && !empty($payload['uid'])){
			$id = absint($payload['uid']);
			$id && $auth = $this->repository->find($id);

			if(!empty($auth)){
				$this->logined = true;
				return $this->auth = $auth;
			}
		}

		return false;
	}

	/**
	 * 判断是否登录
	 */
	public function isLogin(){
		return $this->logined;
	}

	/**
	 * 获取用户信息
	 * 
	 * @param int 	$uid 用户id
	 */
	public function getUserInfo($uid = 0){
		if(!$uid && ! $this->isLogin()){
			return false;
		}

		if($uid != $this->auth->id){
			$auth = $this->repository->find($uid);
			$auth && $this->auth;
		}

		return $this->auth;
	}

	/**
	 * 验证是否有访问数据的权限
	 *
	 * @param string $controller 访问的控制器
	 * @param string $action 	 访问的路由
	 *
	 * @return bool 允许访问为true, 反之为false
	 */
	public function check($controller, $action){
		if(! $this->isLogin()){
			return false;
		}

		if($this->auth->is_admin){
			return true;
		}

		$blocklist = $this->getBlocklist();

		if(isset($blocklist[$controller]) && isset($blocklist[$controller][$action])){
			return false;
		}

		return true;
	}

	/**
	 * 获取禁止列表
	 */
	public function getBlocklist(){
		static $blocklist = [];
		
		if(! $this->isLogin()){
			return false;
		}
		
		$auth_id = $this->auth->id;
		if(isset($blocklist[$auth_id])){
			return $blocklist[$auth_id];
		}

		$adminRepo = new AdminRepository();
		$roleRepo  = new RoleRepository();
		$blocklist = [];
		$results = $adminRepo->getRelationRoles($auth_id);
		$relations = $results[0]->relation;
		$role_ids = [];

		foreach($relations as $relation){
			$role_ids[] = $relation['role_id'];
		}

		$roles = $roleRepo->getBlocklist($role_ids);
		$role_count = $roles->count();

		if($role_count < 1){
			return $blocklist;
		}

		// 数据整理
		// 注: 仅当所有的角色都禁止访问时, 才算禁止访问

		$bucket = [];
		$types = [1 => 'data', 2 => 'page'];

		// 初始化桶
		if($role_count < 2){
			foreach($roles[0]->blocklist as $val){
				$type = $types[$val['type']];
				$blocklist[$auth_id][$type][$val['controller']][] = $val['action']; 
			}

			return $blocklist;
		}else{
			foreach($roles[0]->blocklist as $val){
				$type = $types[$val['type']];
				$bucket[$type][$val['controller']][$val['action']] = 1; 
			}
		}

		// 去掉不重叠的
		$new_bucket = [];
		for($i = 1, $len = $role_count - 1; $i < $len;$i++){
			$new_bucket = [];
			foreach($roles[$i]->blocklist as $val){
				$type = $types[$val['type']];
				$controller = $val['controller'];
				$action = $val['action'];

				if(isset($bucket[$type][$controller][$action])){
					$new_bucket[$type][$controller][$action] = 1;
				}
			}

			$bucket = $new_bucket;
			unset($new_bucket);
		}

		// 整理格式
		foreach($roles[$role_count - 1]->blocklist as $val){
			$type = $types[$val['type']];
			$controller = $val['controller'];
			$action = $val['action'];

			if(isset($bucket[$type][$controller][$action])){
				$blocklist[$auth_id][$type][$controller][] = $action;
			}
		}

		return $blocklist;
	}
}