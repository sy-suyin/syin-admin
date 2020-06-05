<?php
namespace app\client\controller;

use app\client\model\Admin as AdminModel;
use app\client\service\AdminService;
use think\Request;


class Login{

	/**
	 * 登录控制器
	 */
	public function indexAction(Request $request, AdminModel $model){
		// 验证提交数据
		$result = AdminService::requestCheck($model, $request->post(), 'login');

		if(is_error($result)){
			return show_error($result->getError());
		}

		// 登录
		list($data, $model) = $result;
		$admin = AdminService::login($model, $data);

		if(is_error($admin)){
			return show_error($admin->getError());
		}

		// 配置登录后返回的数据
		$result = AdminService::loginConfig($admin);
		$token_info = AdminService::generateToken($admin);
		$result['user'] = $admin->hidden(['password', 'sort'])->toArray();

		return show_success('登录成功', $result)->header([
			'access_token'  => $token_info['access_token'],
			'refresh_token' => $token_info['refresh_token'],
			'refresh_token_url' => $token_info['refresh_token_url'],
			'token_type'    => 'bearer',
			'expires'       => $token_info['token_expire'],
		])->allowCache(false);;
	}
}