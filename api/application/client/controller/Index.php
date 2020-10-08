<?php
namespace app\client\controller;

use app\client\service\AdminService;
use syin\Builder;
use think\Request;

class Index {

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
		$token_expire = AdminService::getTokenExpire(); 
		if(is_error($token)){
			return show_error($token->getError());
		}

		return show_success('请求成功')
			->header([
				'access_token' => $token,
				'token_type'   => 'bearer',
				'expires'      => $token_expire
			])->allowCache(false);
	}

	public function buildAction(){
		$builder = new Builder();
		$builder->build('test');
	}
}