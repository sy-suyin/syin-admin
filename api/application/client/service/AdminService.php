<?php
namespace app\client\service;

use app\common\library\BaseTool;
use app\common\library\RuntimeError;

class AdminService extends BaseTool {

	/**
	 * 登录检测
	 */
	public static function login($model, $data){
		$admin = $model
			->scope('nodeleted')
			->where('login_name', $data['login_name'])
			->find();

		if(empty($admin)){
			return new RuntimeError('登录账号或密码错误1');
		}

		if(!check_password_hash($data['password'], $admin->password)){
			return new RuntimeError('登录账号或密码错误');
		}

		if($admin->is_disabled > 0){
			return new RuntimeError('登录账号已被禁用, 请联系管理');
		}

		request()->admin = $admin;

		return $admin;
	}

	/**
	 * 登录时返回账号相关的配置
	 */
	public static function loginConfig($model){
		$forbid = self::getForbidData($model->id);
		$domain = request()->domain();
		$domain = trim($domain, '/');

		return [
			'forbid' =>	$forbid,
			'config' => array(
				'domain' => $domain,
				'sidebar_imgs' => [
					$domain.'/static/api/sidebar/bg-1.jpg',
					$domain.'/static/api/sidebar/bg-2.jpg',
					$domain.'/static/api/sidebar/bg-3.jpg',
					$domain.'/static/api/sidebar/bg-4.jpg',
				],
			)
		];
	}

	/**
	 * 获取登录时的角色信息
	 */
	public static function profileParams($model, $params, $scene){
		$result = self::requestCheck($model, $params, $scene);

		if(is_error($result)){
			return $result;
		}

		list($data, $model) = $result;

		if(!empty($data['password'])){
			if(!check_password_hash($data['oldpwd'], $model->password)){
				return new RuntimeError('原始密码输入有误');
			}

			if($data['oldpwd'] == $data['password']){
				return new RuntimeError('新密码不能与旧密码相同');
			}

			if($data['confirmpwd'] != $data['password']){
				return new RuntimeError('确认密码输入有误');
			}

			if(!check_password_strength($data['password'])){
				return new RuntimeError('密码格式有误');
			}

			$args['password'] = generate_password_hash($data['password']);
		}else{
			unset($data['password']);
		}

		if($data['avatar']){
			$avatar = '/static/common/img/avatar/'.$data['avatar'].'.png';

			if(is_file(env('root_path').'public/'.$avatar)){
				$data['avatar'] = $avatar;
			}
		}

		unset($data['confirmpwd']);
		unset($data['oldpwd']);

		return $data;
	}

	/**
	 * 获取权限黑名单数据
	 *
	 * @param int $admin_id 管理员账号id
	 *
	 */
	public static function getForbidData($admin_id){
		// 获取关联角色id;
		$role_ids = db('admin_role_relation')->where('admin_id', $admin_id)->column('role_id');

		// 禁止访问数据权限
		$forbid = [
			'data_forbid' => [],
			'page_forbid' => [],
		];

		if(empty($role_ids)){
			return $forbid;
		}

		$forbid_data = db('admin_role_ban')->field('id, controller, action, type')->where('role_id', 'in', $role_ids)->select();

		// 计算规则, 整合成多个对象的数据组, 仅当各权限名单中都有的才算禁止(求交集)
		if(!empty($forbid_data)){
			$data_forbid = [];
			$page_forbid = [];
			$role_count = count($role_ids);

			foreach($forbid_data as $val){
				$target = '';
				if($val['type'] == 1){
					$target = 'data_forbid';
				}elseif($val['type'] == 2){
					$target = 'page_forbid';
				}

				if(! isset($$target[$val['controller']])){
					$$target[$val['controller']] = [];
				}

				if(! isset($$target[$val['controller']][$val['action']])){
					$$target[$val['controller']][$val['action']] = 0;
				}

				$$target[$val['controller']][$val['action']] += 1;
			}

			// 判断是否所有角色都禁止改权限
			foreach($data_forbid as $controller => $actions){
				foreach($actions as $action => $count){
					if($count >= $role_count){
						if(! isset($forbid['data_forbid'][$controller])){
							$forbid['data_forbid'][$controller] = [];
						}
						$forbid['data_forbid'][$controller][] = $action;
					}
				}
			}

			foreach($page_forbid as $controller => $actions){
				foreach($actions as $action => $count){
					if($count >= $role_count){
						if(! isset($forbid['page_forbid'][$controller])){
							$forbid['page_forbid'][$controller] = [];
						}
						$forbid['page_forbid'][$controller][] = $action;
					}
				}
			}
		}

		return $forbid;
	}

	/**
	 * 验证是否有访问数据的权限
	 *
	 * @param object/int $admin  管理员数据或管理员id
	 * @param string $controller 访问的控制器
	 * @param string $action 	 访问的路由
	 *
	 * @return bool 允许访问为true, 反之为false
	 */
	public static function verifyPermission($admin, $controller, $action){
		static $data_forbid = [];

		// 此处获取用户信息
		if(is_numeric($admin)){
			$admin = model('admin')->getById($admin, 'id, name, is_admin');
		}

		if(empty($admin)){
			return false;
		}

		if($admin['is_admin']){
			return true;
		}

		// 获取关联角色id;
		$role_ids = db('admin_role_relation')->where('admin_id', $admin['id'])->column('role_id');

		if(empty($role_ids)){
			return true;
		}

		if(empty($data_forbid)){
			$forbid_data = db('admin_role_ban')->field('id, controller, action')->where('type', 1)->where('role_id', 'in', $role_ids)->select();

			// 计算规则, 整合成多个对象的数据组, 仅当各权限名单中都有的才算禁止(求交集)
			if(!empty($forbid_data)){
				$temp = [];
				$role_count = count($role_ids);

				foreach($forbid_data as $val){
					if(! isset($temp[$val['controller']])){
						$temp[$val['controller']] = [];
					}

					if(! isset($temp[$val['controller']][$val['action']])){
						$temp[$val['controller']][$val['action']] = 0;
					}

					$temp[$val['controller']][$val['action']] += 1;
				}

				// 判断是否所有角色都禁止改权限
				foreach($temp as $controller => $actions){
					foreach($actions as $action => $count){
						if($count >= $role_count){
							if(! isset($data_forbid[$controller])){
								$data_forbid[$controller] = [];
							}
							$data_forbid[$controller][] = $action;
						}
					}
				}
			}
		}

		if(isset($data_forbid[$controller]) && isset($data_forbid[$controller][$action])){
			return false;
		}

		return true;
	}
}