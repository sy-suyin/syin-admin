<?php
namespace app\client\controller;

use app\client\model\Admin as AdminModel;
use app\client\model\Role as RoleModel;
use app\client\service\SystemService;
use think\Request;

class System {

	/**
	 * 管理员管理 - 列表
	 */
	public function adminListAction(Request $request, AdminModel $model){
		// 1. 获取查询参数
		$params = SystemService::adminListParams($request->post());

		// 2. 查询数据
		$result = SystemService::page($model, $params);
		$result['data' ] = SystemService::adminMultiRelationRoles($result['data']);

		return show_success('', $result);
	}

	/**
	 * 管理员管理 - 回收站
	 */
	public function adminRecycleAction(Request $request, AdminModel $model){
		// 1. 获取查询参数
		$params = SystemService::adminListParams($request->post(), true);

		// 2. 查询数据
		$result = SystemService::page($model, $params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 管理员数据
	 */
	public function adminDataAction(Request $request, AdminModel $model){
		$result = SystemService::getData($model, $request->get());

		if(empty($result)){
			return show_error('未找到相关数据');
		}

		$result = $result->toArray();
		$result['roles'] = SystemService::adminRelationRoles($result['id']);

		return show_success('查询成功', $result);
	}

	/**
	 * 管理员管理 - 添加
	 */
	public function adminAddAction(Request $request, AdminModel $model){
		// 验证提交数据
		$result = SystemService::requestCheck($model, $_POST, 'add');

		if(is_error($result)){
			return show_error($result->getError());
		}

		// 保存数据
		list($data, $model) = $result;
		$save = SystemService::adminSave($model, $data);

		if(! $save){
			return show_error('添加失败，请稍后重试');
		}

		// 保存角色关联权限信息
		SystemService::adminRoleSave($model, $data['roles'], false);

		// 保存日志

		// 返回消息
		return show_success('已成功添加角色');
	}

	/**
	 * 管理员管理 - 修改
	 */
	public function adminEditAction(Request $request, AdminModel $model){
		// 验证提交数据
		$result = SystemService::requestCheck($model, $_POST, 'edit');

		if(is_error($result)){
			return show_error($result->getError());
		}

		// 保存数据
		list($data, $model) = $result;
		$save = SystemService::adminSave($model, $data);

		if(! $save){
			return show_error('修改失败，请稍后重试');
		}

		// 保存角色关联权限信息
		SystemService::adminRoleSave($model, $data['roles'], true);

		// 保存日志

		// 返回消息
		return show_success('已成功修改角色');
	}

	/**
	 * 管理员管理 - 删除
	 */
	public function admindelAction(AdminModel $model){
		$result = SystemService::deletedItemLogically($model, '管理员');
		
		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_success('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 管理员管理 - 禁用
	 */
	public function admindisAction(AdminModel $model){
		$result = SystemService::disableItem($model, '管理员');

		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_success('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 管理员管理 - 排序
	 */
	public function adminsortAction(AdminModel $model){
		$result = SystemService::sortItem($model, '管理员');

		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_success('操作失败：'.$result['msg']);
		}
	}



	/**
	 * 角色管理 - 列表
	 */
	public function roleListAction(Request $request, RoleModel $model){
		// 1. 获取查询参数
		$params = SystemService::roleListParams($request->post());

		// 2. 查询数据
		$result = SystemService::page($model, $params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 回收站
	 */
	public function roleRecycleAction(Request $request, RoleModel $model){
		// 1. 获取查询参数
		$params = SystemService::roleListParams($request->post(), true);

		// 2. 查询数据
		$result = SystemService::page($model, $params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 角色数据
	 */
	public function roleDataAction(Request $request, RoleModel $model){
		$result = SystemService::getData($model, $request->get());

		if(empty($result)){
			return show_error('未找到相关数据');
		}

		return show_success('查询成功', $result->toArray());
	}

	/**
	 * 角色管理 - 添加
	 */
	public function roleAddAction(Request $request, RoleModel $model){
		// 获取提交数据
		$params = $_POST;

		// 验证提交数据
		$result = SystemService::requestCheck($model, $params, 'add');
		$blocklist = SystemService::roleBlocklistCheck($params);

		if(is_error($result)){
			return show_error($result->getError());
		}

		// 保存数据
		list($data, $model) = $result;
		$save = SystemService::saveData($model, $data);

		if(! $save){
			return show_error('添加失败，请稍后重试');
		}

		// 保存角色权限
		$data['id'] = $model->id;
		SystemService::roleBlocklistSave($data, $blocklist, false);

		// 保存日志

		// 返回消息
		return show_success('已成功添加角色');
	}

	/**
	 * 角色管理 - 修改
	 */
	public function roleEditAction(Request $request, RoleModel $model){
		// 获取提交数据
		$params = $_POST;

		// 验证提交数据
		$result = SystemService::requestCheck($model, $params, 'edit');
		$blocklist = SystemService::roleBlocklistCheck($params);

		if(is_error($result)){
			return show_error($result->getError());
		}

		// 保存数据
		list($data, $model) = $result;
		$save = SystemService::saveData($model, $data);

		if(! $save){
			return show_error('添加失败，请稍后重试');
		}

		// 保存角色权限
		$data['id'] = $model->id;
		SystemService::roleBlocklistSave($data, $blocklist, true);

		// 保存日志

		// 返回消息
		return show_success('已成功修改角色');
	}

	/**
	 * 角色管理 - 删除
	 */
	public function roledelAction(RoleModel $model){
		$result = SystemService::deletedItemLogically($model, '角色');

		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_success('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 角色管理 - 禁用
	 */
	public function roledisAction(RoleModel $model){
		$result = SystemService::disableItem($model, '角色');

		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_success('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 获取所有角色数据
	 */
	public function roleAllAction(RoleModel $model){
		$results = RoleModel::getAll();
		return show_success('', $results);
	}

	/**
	 * 获取数据权限信息
	 */
	public function getAccessDataAction(){
		// 角色id
		$id = absint(input('id'));
		
		// 数据权限配置数据
		$config = config('access.');

		// 获取角色的禁止权限信息
		$blocklist = SystemService::getRoleBlocklist($id, $config);

		return show_success('', [
			'blocklist' => $blocklist,
			'config' 	=> $config,
		]);
	}
}