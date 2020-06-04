<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\client\service\AdminService;
use think\Request;

class Index extends Client {

	/**
     * 个人中心
     */
    public function profileAction(Request $request){
		// 验证提交数据
		$params = $request->post();
		$params['id'] = $request->admin->id;
		$data = AdminService::profileParams($request->admin, $params, 'profile');

		if(is_error($data)){
			return show_error($data->getError());
		}

		// 保存数据
		$save = AdminService::saveData($request->admin, $data);

		if(! $save){
			return show_error('修改失败，请稍后重试');
		}

		// 保存日志

		// 返回消息
		return show_success('已成功修改个人信息');
	}

	/**
	 * 获取新token
	 */
	public function refreshTokenAction(Request $request){
		$token = AdminService::refreshToken($request->post());

		if(is_error($token)){
			return show_error($token->getError());
		}

		return show_success('', [
			'token' => $token
		]);
	}
}