<?php
namespace app\client\controller;

use app\client\model\Admin as AdminModel;
use app\client\service\AdminService;
use app\client\service\TokenService;
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
		$result['user'] = $admin->hidden(['password', 'sort'])->toArray();

		// 生成token
		$result['token'] = TokenService::generateToken([
			'uid' => $admin->id
		]);

		$result['refresh_token'] = TokenService::generateRefreshToken([
			'uid' => $admin->id
		]);

		return show_success('登录成功', $result);
	}
}