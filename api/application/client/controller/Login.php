<?php
namespace app\client\controller;

use app\client\service\LoginService;
use think\Request;

class Login{

	/**
	 * 登录控制器
	 */
	public function index(Request $request){
		$admin = LoginService::login();
		LoginService::generateToken($admin->id, true);
		// 配置登录后返回的数据
		$result = LoginService::loginConfig($admin);

		// 设置标题(日志)
		$request->title = '管理员登录';
		return show_success('登录成功', $result)->allowCache(false);
	}

	/**
	 * 使用token重新发起登录请求
	 */
	public function refresh(Request $request){
		if(empty($request->admin)){
			return show_error('未找到相关数据');
		}

		$result = LoginService::loginConfig($request->admin);
		$result['user'] = $request->admin;

		return show_success('', $result);
	}
}