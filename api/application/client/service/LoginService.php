<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\RuntimeError;
use app\common\library\DbTool;

class LoginService extends BaseService {

	/**
	 * 登录检测
	 */
	public static function login(){
		$fields = [
			'login',
			'password',
		];

		$validation = [
			'rules' => [
				'login'      	=> 'require',
				'password'   	=> 'require',
			],
			'msgs'  => [
				'name.require' 	 	=> '请先输入名称',
				'password.require'  => '登录密码不能为空',
			]
		];

		// 数据筛选过滤
		$args = self::filterParmas($fields, $_POST);

		// 验证参数
		self::validate($args, $validation['rules'], $validation['msgs']);

		$auth = Auth::getInstance();
		$result = $auth -> login($args['login'], $args['password']);

		if(is_error($result)){
			throw $result;
		}

		return $result;
	}

	/**
	 * 登录时返回账号相关的配置
	 */
	public static function loginConfig($admin){
		$blocklist = self::getBlocklist($admin->id);
		$domain = request()->domain();
		$domain = trim($domain, '/');
		$sidebar_imgs = config('common.sidebar_imgs');

		$result = [
			'user'		=> $admin->hidden(['password', 'sort'])->toArray(),
			'blocklist' => $blocklist,
			'config' => array(
				'domain' => $domain,
				'sidebar_imgs' => $sidebar_imgs
			)
		];

		return $result;
	}

	/**
	 * 获取token有效时间
	 */
	public static function getTokenExpire(){
		return config('auth. token_expire');
	}

	/**
	 * 获取权限黑名单数据
	 *
	 * @param int $admin_id 管理员账号id
	 *
	 */
	public static function getBlocklist($admin_id){
		// 获取关联角色id;
		$role_ids = db('admin_role_relation')->where('admin_id', $admin_id)->column('role_id');

		// 禁止访问数据权限
		$blocklist = [
			'data' => [],
			'page' => [],
		];

		if(empty($role_ids)){
			return $blocklist;
		}

		$results = db('admin_role_blocklist')->field('id, controller, action, type')->where('role_id', 'in', $role_ids)->select();

		// 计算规则, 整合成多个对象的数据组, 仅当各权限名单中都有的才算禁止(求交集)
		if(!empty($results)){
			$role_count = count($role_ids);
			$stat = [
				'data' => [],
				'page' => [],
			];

			$types = [1 => 'data', 2 => 'page'];
			foreach($results as $val){
				$type = $types[$val['type']];
				$controller = $val['controller'];
				$action = $val['action'];

				if(! isset($stat[$type][$controller])){
					$stat[$type][$controller] = [];
				}

				if(! isset($stat[$type][$controller][$action])){
					$stat[$type][$controller][$action] = 0;
				}

				$stat[$type][$controller][$action] = 1;
			}

			// 判断是否所有角色都禁止改权限
			foreach($types as $type){
				if(empty($stat[$type])){
					continue;
				}

				foreach($stat[$type] as $controller => $actions){
					foreach($actions as $action => $total){
						if($total < $role_count){
							continue;
						}

						$blocklist[$type][$controller][] = $action;
					}
				}
			}
		}

		return $blocklist;
	}

	/**
	 * 生成token
	 * 
	 * @param int   $admin_id 		管理员id
	 * @param bool  $with_refresh	是否附带生成刷新token所需的数据
	 * @param array $data			生成token时所附带的额外数据
	 *
	 */
	public static function generateToken($admin_id, $with_refresh = false, $data = []){
		$data['uid'] = $admin_id;

		// 生成token
		$result = [
			'token_type' => 'bearer',
			'access_token' => TokenService::generateToken($data),
			'token_expire' => config('auth. token_expire'),
		];

		if($with_refresh){
			$result['refresh_token'] = TokenService::generateRefreshToken(['uid' => $admin_id]);
			$result['refresh_token_url'] = url('index/refreshtoken', '', true, true);
		}

		foreach($result as $key => $val){
			header("{$key}: {$val}");
		}

		return $result;
	}

	/**
	 * 刷新token
	 */
	public static function refreshToken($params){
		$auth = \app\client\service\Auth::getInstance();

		if(empty($params)){
			return new RuntimeError('refresh_token异常');
		}

		$refresh_token = isset($params['refresh_token']) ? $params['refresh_token'] : '';

		if(empty($refresh_token)){
			return new RuntimeError('token生成失败');
		}

		$payload = TokenService::verifyRefreshToken($refresh_token);

		if(!$payload || empty($payload['uid'])){
			return new RuntimeError('token生成失败');
		}

		$uid = absint($payload['uid']);
		$admin = $auth->getUserInfo($uid);
		$result = self::generateToken($admin['id'], false);

		return $result;
	}
}