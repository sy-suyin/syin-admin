<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\client\library\SystemTool;
use app\common\library\BaseTool;
use think\Request;

class System extends Client {

	/**
	 * 管理员 - 列表
	 */
	public function adminlistAction(){
		$result	= SystemTool::getAdminResultsArgs(false);
		$num = 10;
		$results = $result['model']->paginate($num, false, ['query'=>$result['args']]);
		$results = $results->toArray();

		$results['data' ] = SystemTool::convertTime($results['data']);
		$results['data' ] = SystemTool::getAdminRelation($results['data']);

		return show_success('', [
			'total' => $results['total'],
			'current_page' => $results['current_page'],
			'page_max' => ceil($results['total'] / $num),
			'page_num' => $num,
			'results'  => $results['data'],
		]);
	}

	/**
	 * 管理员 - 添加
	 */
	public function adminaddAction(Request $request){
		$result = SystemTool::getAdminArgs();

		if(is_error($result)){
			return show_error($result->getErrorMsg());
		}

		if(! $result['model']->save()){
			return show_error('新建失败，请稍后重试');
		}

		$relation_args = [];
		foreach($result['roles'] as $role){
			$relation_args[] = [
				'admin_id' => $result['model']->id,
				'role_id'  => $role,
			];
		}

		db('admin_role_relation')->insertAll($relation_args);
		// $request->log = '管理员'.($request->admin->name).', 新建了管理员'.$result['model']->name;
		return show_success('已成功添加管理员');
	}

	/**
	 * 管理员 - 修改
	 */
	public function admineditAction(Request $request){
		$id = absint(input('id'));

		if($id){
			$model = \app\client\model\Admin::get($id);
		}

		if(empty($model)){
			return show_error('未找到相关信息');
		}

		if(! $request->isPost()){
			return show_error('请求失败，请稍后重试');
		}

		$result = SystemTool::getAdminArgs(true, $model);
		if(is_error($result)){
			return show_error($result->getErrorMsg());
		}

		if(! $result['model']->save()){
			return show_error('修改失败，请稍后重试');
		}

		$relation_args = [];
		foreach($result['roles'] as $role){
			$relation_args[] = [
				'admin_id' => $id,
				'role_id'  => $role,
			];
		}

		// 先接触绑定,再建立新的绑定
		db('admin_role_relation')->where('admin_id', $id)->delete();
		db('admin_role_relation')->insertAll($relation_args);
		// $request->log = '管理员'.($request->admin->name).', 修改了管理员'.$result['model']->name;
		return show_success('已成功修改管理员数据');
	}

	/**
	 * 获取管理员详情
	 */
	public function admindetailAction(){
		$id = absint(input('id'));

		if($id){
			$model = \app\client\model\Admin::get($id);
		}

		if(empty($model)){
			return show_error('未找到相关信息');
		}

		$roles = db('admin_role_relation')
			->field('r.id, name')
			->alias('rr')
			->where('admin_id', $id)
			->join('admin_role r', 'r.id = rr.role_id')
			->select();

		$result = $model -> toArray();
		unset($result['password']);

		$result['avatar_url'] = request()->domain().$result['avatar'];
		$result['roles'] = empty($roles) ? [] : $roles;

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 列表
	 */
	public function rolelistAction(){
		$result	= SystemTool::getRoleResultsArgs(false);
		$num = config('common.page_num');
		$results = $result['model']->paginate($num, false, ['query'=>$result['args']]);
		$results = $results->toArray();

		$results['data' ] = SystemTool::convertTime($results['data']);

		return show_success('', [
			'total' => $results['total'],
			'current_page' => $results['current_page'],
			'page_max' => ceil($results['total'] / $num),
			'page_num' => $num,
			'results'  => $results['data'],
		]);
	}

	/**
	 * 获取所有角色数据
	 */
	public function getallrolesAction(){
		$results = db('admin_role')
			->field('id, name, description')
			->where('is_disabled', 0)
			->where('is_deleted', 0)
			->select();

		return show_success('', $results);
	}

	/**
	 * 获取角色详情
	 */
	public function roledetailAction(){
		$id = absint(input('id'));

		if($id){
			$model = \app\client\model\Role::get($id);
		}

		if(empty($model)){
			return show_error('未找到相关信息');
		}

		$result = $model -> toArray();
		return show_success('', $result);
	}

	/**
	 * 添加角色
	 */
	public function roleaddAction(Request $request){
		if(! $request->isPost()){
			return show_error('请求失败，请稍后重试');
		}

		$result = SystemTool::getRoleArgs();
		if(is_error($result)){
			return show_error($result->getErrorMsg());
		}

		if(! $result['model']->save()){
			return show_error('添加失败，请稍后重试');
		}

		$bans = [];

		$role_id = $result['model']->id;

		$module = $request->module();

		if(!empty($result['data_forbid'])){
			foreach($result['data_forbid'] as $val){
				$val['module'] = $module;
				$val['role_id'] = $role_id;
				$bans[] = $val;
			}
		}

		if(!empty($result['page_forbid'])){
			foreach($result['page_forbid'] as $val){
				$val['module'] = $module;
				$val['role_id'] = $role_id;
				$bans[] = $val;
			}
		}

		if(!empty($bans)){
			db('admin_role_ban')->insertAll($bans);
		}

		// $request->log = '管理员'.($request->admin->name).', 添加了新角色'.$result['model']->name;
		return show_success('已成功添加角色');
	}

	/**
	 * 添加角色
	 */
	public function roleeditAction(Request $request){
		$id = absint(input('id'));

		if($id){
			$model = \app\client\model\Role::get($id);
		}

		if(empty($model)){
			return show_error('未找到相关信息');
		}

		if(! $request->isPost()){
			return show_error('请求失败，请稍后重试');
		}

		$result = SystemTool::getRoleArgs(true, $model);
		if(is_error($result)){
			return show_error($result->getErrorMsg());
		}

		if(! $result['model']->save()){
			return show_error('添加失败，请稍后重试');
		}

		// 处理权限数据
		$data_forbid_edit = !empty($_POST['data_forbid_edit']) ? true : false;
		$page_forbid_edit = !empty($_POST['page_forbid_edit']) ? true : false;
		$role_id = $result['model']->id;
		$module = $request->module();
		$bans = [];

		if($data_forbid_edit){
			if(!empty($result['data_forbid'])){
				foreach($result['data_forbid'] as $val){
					$val['module'] = $module;
					$val['role_id'] = $role_id;
					$bans[] = $val;
				}
			}

			// 直接删除旧数据, 再重新插入新数据
			db('admin_role_ban')->where('role_id', $role_id)->where('type', 1)->delete();
		}

		if($page_forbid_edit){
			if(!empty($result['page_forbid'])){
				foreach($result['page_forbid'] as $val){
					$val['module'] = $module;
					$val['role_id'] = $role_id;
					$bans[] = $val;
				}
			}

			db('admin_role_ban')->where('role_id', $role_id)->where('type', 2)->delete();
		}

		if(!empty($bans)){
			db('admin_role_ban')->insertAll($bans);
		}

		// $request->log = '管理员'.($request->admin->name).', 修改了角色'.$result['model']->name;
		return show_success('已成功修改角色');
	}

	/**
	 * 角色管理 - 删除
	 */
	public function roledelAction(Request $request){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$deleted = absint(input('operate'));
		$operation_type = $deleted ? '删除' : '恢复';

		$result = \app\client\model\Role::deletedItemLogically($id, $deleted);

		if(is_error($result)){
			return show_error($result->getErrorMsg());
		}

		// $request->log = '管理员'.($request->admin->name).', 共'.$operation_type.$result.'个角色';
		return show_success('操作成功, 共'.$operation_type.$result.'个角色');
	}

	/**
	 * 角色管理 - 禁用
	 */
	public function roledisAction(Request $request){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$disabled = absint(input('operate'));
		$operation_type = $disabled ? '禁用' : '启用';

		$result = \app\client\model\Role::disableItem($id, $disabled);

		if(is_error($result)){
			return show_error($result->getErrorMsg());
		}

		// $request->log = '管理员'.($request->admin->name).', 共'.$operation_type.$result.'个角色';
		return show_success('操作成功, 共'.$operation_type.$result.'个角色');
	}

	/**
	 * 获取数据权限信息
	 */
	public function getaccessdataAction(){
		// 数据权限配置数据
		$config = config('access.');
		// 角色id
		$id = absint(input('id'));
		// 禁止访问数据权限
		$forbid = [
			'data_forbid' => [],
			'page_forbid' => [],
		];

		if($id > 0){
			$forbid_data = db('admin_role_ban')->where('role_id', $id)->select();

			if(!empty($forbid_data)){
				foreach($forbid_data as $val){
					if($val['type'] == 1){
						$forbid['data_forbid'][] = $val;
					}elseif($val['type'] == 2){
						$forbid['page_forbid'][] = $val;
					}
				}
			}
		}

		return show_success('', [
			'forbid' => $forbid,
			'config' => $config,
		]);
	}

}