<?php
namespace app\client\controller;

use app\client\model\Admin as AdminModel;
use app\client\service\AdminService;
use app\client\service\LoginService;
use app\client\service\Auth;
use think\Request;


class Login{

	/**
	 * 登录控制器
	 */
	public function indexAction(Request $request, AdminModel $model){
		$admin = Auth::login('admin');
		request()->auth = $admin;

		// 配置登录后返回的数据
		$result = LoginService::loginConfig($admin);
		$token_info = Auth::generateToken($admin);
		$result['user'] = $admin->hidden(['password', 'sort'])->toArray();

		return show_success('登录成功', $result)->header([
			'access_token'  => $token_info['access_token'],
			'refresh_token' => $token_info['refresh_token'],
			'refresh_token_url' => $token_info['refresh_token_url'],
			'token_type'    => 'bearer',
			'expires'       => $token_info['token_expire'],
		])->allowCache(false);;
	}

	/**
	 * 使用token重新发起登录请求
	 */
	public function refreshAction(Request $request){
		if(empty($request->admin)){
			return show_error('未找到相关数据');
		}

		$result = AdminService::loginConfig($request->admin);
		$result['user'] = $request->admin;

		return show_success('', $result);
	}
}