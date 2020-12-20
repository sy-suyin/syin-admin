<?php
namespace app\client\controller;

use app\client\repository\AdminRepository;
use app\client\service\AdminService;
use think\Request;

class Admin {

	/**
	 * 管理员管理 - 列表
	 */
	public function index(AdminRepository $repository){
		// 1. 获取查询参数
		$params = AdminService::adminListParams();

		// 2. 查询数据
		$result = $repository->paginate($params);
		$result['data'] = AdminService::adminMultiRelationRoles($repository, $result['data']);

		return show_success('', $result);
	}

	/**
	 * 管理员管理 - 回收站
	 */
	public function recycle(AdminRepository $repository){
		// 1. 获取查询参数
		$params = AdminService::adminListParams();

		// 2. 查询数据
		$result = $repository->withDeleted()->paginate($params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 管理员数据
	 */
	public function read(AdminRepository $repository){
		$result = AdminService::getRecord($repository);

		$result = $result->toArray();
		$result['roles'] = AdminService::adminRelationRoles($result['id']);

		return show_success('查询成功', $result);
	}

    /**
     * 获取创建数据前所需数据
     * GET
     */
    public function create(AdminRepository $repository){
        return show_success('查询成功');
    }

	/**
	 * 管理员管理 - 添加
	 */
	public function save(AdminRepository $repository){
		$args   = AdminService::checkRequest();
		$result = $repository->create($args);

		if(! $result){
			return show_error('保存失败');
		}

		// 保存角色关联权限信息
		AdminService::adminRoleSave($result, $args['roles'], false);

		// 保存日志
		request()->title = '添加管理员';

		// 返回消息
		return show_success('已成功添加管理员数据');
	}

	public function edit(AdminRepository $repository){

	}

	/**
	 * 管理员管理 - 修改
	 */
	public function update(AdminRepository $repository){
		$model = AdminService::getRecord($repository, $_POST);
		$args  = AdminService::checkRequest($model);

		$result = $repository->update($args, $model->id);

		if(! $result){
			return show_error('保存失败');
		}

		// 保存角色关联权限信息
		AdminService::adminRoleSave($model, $args['roles'], true);

		// 保存日志
		request()->title = '修改管理员';

		return show_success('已成功修改管理员数据');
	}

	/**
	 * 管理员管理 - 删除
	 */
	public function del(AdminRepository $repository){
		// 管理员数据较特殊, 超级管理员不允许删除
		$repository->where(['is_admin' => 0]);
		$result = AdminService::delete($repository, '管理员');

		if($result['status']){
			request()->title = '删除管理员';
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_error('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 管理员管理 - 禁用
	 */
	public function dis(AdminRepository $repository){
		$result = AdminService::disableItem($repository, '管理员');
		request()->title = '管理员禁用';
		return json($result);
	}

	/**
	 * 管理员管理 - 排序
	 */
	public function sort(AdminRepository $repository){
		$result = AdminService::sortItem($repository, 'sort', '99');
		request()->title = '管理员排序';
		return json($result);
	}
}