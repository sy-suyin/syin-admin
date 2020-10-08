<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\RuntimeError;
use app\common\library\DbTool;

class RoleService extends BaseService {

	/**
	 * 检查提交的数据
	 */
	public static function checkRequest($model = null) {
		$is_edit = is_null($model) ? false : true;
		$fields = [
			'name',
			'description' => 'desc',
		];

		$validation = [
			'rules' => [
				'name'      	=> 'require|unique:admin_role',
			],
			'msgs'  => [
				'name.require' 	 => '请先输入角色名称',
				'name.unique' 	 => '名称已被占用'
			]
		];

		// 数据筛选过滤
		$args = self::filterParmas($fields, $_POST);

		// 验证参数
		$is_edit && $args['id'] = $model->id;
		$valid = self::validate($args, $validation['rules'], $validation['msgs']);
		
		return $args;
	}

	
	/**
	 * 角色禁止名单数据检查
	 */
	public static function roleBlocklistCheck(){
		$blocklist = obtain('blocklist/a', [], false, $_POST);

		if(empty($blocklist)){
			return [
				'data' => [],
				'page' => [],
			];
		}

		$blocklist_data = obtain('data/a', [], false, $blocklist);
		$blocklist_page = obtain('page/a', [], false, $blocklist);

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