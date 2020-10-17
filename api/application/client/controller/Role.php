<?php
namespace app\client\controller;

use app\client\repository\RoleRepository;
use app\client\model\Role as RoleModel;
use app\client\service\RoleService;
use think\Request;

class Role{

	/**
	 * 角色管理 - 列表
	 */
	public function index(RoleRepository $repository){
		// 1. 获取查询参数
		$params = RoleService::getListParams($_POST);

		// 2. 查询数据
		$result = $repository->paginate($params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 回收站
	 */
	public function recycle(RoleRepository $repository){
		// 1. 获取查询参数
		$params = RoleService::getListParams($_POST);

		// 2. 查询数据
		$result = $repository->withDeleted()->paginate($params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 角色数据
	 */
	public function detail(RoleRepository $repository){
		$model = RoleService::getRecord($repository);

		return show_success('查询成功', $model->toArray());
	}

	/**
	 * 角色管理 - 添加
	 */
	public function add(RoleRepository $repository){
		// 获取提交数据
		$args = RoleService::checkRequest();
		$blocklist = RoleService::roleBlocklistCheck();

		// 保存数据
		$result = $repository->create($args);

		if(! $result){
			return show_error('保存失败');
		}

		// 保存角色权限
		$data['id'] = $result->id;
		RoleService::roleBlocklistSave($data, $blocklist, false);

		// 保存日志

		// 返回消息
		return show_success('已成功添加角色');
	}

	/**
	 * 角色管理 - 修改
	 */
	public function edit(RoleRepository $repository){
		$model = RoleService::getRecord($repository, $_POST);

		// 获取提交数据
		$args = RoleService::checkRequest($model);
		$blocklist = RoleService::roleBlocklistCheck();

		// 保存数据
		$result = $repository->update($args, $model->id);

		if(! $result){
			return show_error('添加失败，请稍后重试');
		}

		// 保存角色权限
		$data['id'] = $model->id;
		RoleService::roleBlocklistSave($data, $blocklist, true);

		// 保存日志

		// 返回消息
		return show_success('已成功修改角色');
	}

	/**
	 * 角色管理 - 删除
	 */
	public function del(RoleRepository $repository){
		$result = RoleService::delete($repository, '角色');

		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_error('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 角色管理 - 禁用
	 */
	public function dis(RoleRepository $repository){
		$result = RoleService::disableItem($repository, '角色');
		return json($result);
	}

	/**
	 * 角色管理 - 排序
	 */
	public function sort(RoleRepository $repository){
		$result = RoleService::sortItem($repository, 'sort', '99');
		return json($result);
	}

	/**
	 * 获取所有角色数据
	 */
	public function all(RoleRepository $repository){
		$results = $repository->all();
		return show_success('', $results);
	}
}