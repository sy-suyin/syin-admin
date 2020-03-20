<?php
namespace app\client\library;

use \app\common\library\RuntimeError;
use app\common\library\BaseTool;

class AdminTool extends BaseTool{

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
			$admin = db('admin')->getById($admin, 'id, name, is_admin');
	
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