<?php
namespace app\client\controller;

use app\client\repository\AdminRepository;
use app\client\service\AdminService;
use think\Request;

class Index {

	/**
     * 个人中心
     */
    public function profile(AdminRepository $repository){
		$admin = request()->admin;
		// 验证提交数据
		$args = AdminService::getProfileParams($admin);

		$result = $repository->update($args, $admin->id);

		if(! $result){
			return show_error('保存失败');
		}

		// 保存日志

		// 返回消息
		return show_success('已成功修改个人信息');
	}

	/**
	 * 获取新token
	 */
	public function refreshToken(Request $request){
		$token = \app\client\service\LoginService::refreshToken($request->post());

		if(is_error($token)){
			return show_error($token->getError());
		}

		return show_success('请求成功')->allowCache(false);
	}
}