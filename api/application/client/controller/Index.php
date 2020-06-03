<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\client\service\AdminService;
use app\client\service\TokenService;
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
	public function refreshTokenAction(){
		$refresh_token = input('refresh_token/s', '');
		$refresh_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTEzNzI3NjEsIm5iZiI6MTU5MTE5OTk2MSwiaWF0IjoxNTkxMTk5OTYxLCJjbGllbnRfaWQiOiIiLCJ1aWQiOjEsImhhc2giOiJjMjBkZTUzMWEyYTRhZTAzYjI1Zjc2NjZlMmNhZmI4YSJ9.hHF3Z6WTl2FS2BRSg2RDrpD0_1tHj-YUReDT0bekMN8";


		if(empty($refresh_token)){
			return show_error('token生成失败');
		}

		$payload = TokenService::verifyRefreshToken($refresh_token);
						
		if(!$payload || empty($payload['uid'])){
			return show_error('token生成失败');
		}

		$admin = db('admin')
			->where('id', $payload['uid'])
			->where('is_disabled',0)
			->where('is_deleted',0)
			->find();
	
		$token = TokenService::generateToken([
			'uid' => $admin['id']
		]);

		return show_success('', [
			'token' => $token
		]);
	}
}