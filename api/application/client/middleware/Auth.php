<?php
/** 
 * 客户端访问控制中间件
 */
namespace app\client\middleware;

use app\client\service\AdminService;
use app\client\service\TokenService;
use app\client\model\Admin as AdminModel;

class Auth{

	public function handle($request, \Closure $next, $name){
		// 用户身份验证TOKEN
		$token = $request->header('Authorization');
		$controller = strtolower($request->controller());
		$action = strtolower($request->action());

		// 刷新 token
		if($controller == 'index' && $action == 'refreshtoken'){
			return $next($request);
		}

		$result = $this->tokenVerify($token);

		if($result == false){
			// 未登录或token已过期
			$whitelist = config('auth.whitelist');

			if(empty($whitelist) || !isset($whitelist[$controller]) || !in_array($action, $whitelist[$controller])){
				return json('请在登陆后重试', 401)->header([
					'WWW-Authenticate' => 'Bearer, error="invalid_token", error_description="The access token expired"'
				]);
			}
		}else{
			$request->admin = $result;

			// 权限验证
			$verify_res = AdminService::verifyPermission($result, $controller, $action);

			if(false == $verify_res){
				return show_error('对不起，您没有权限执行该操作');
			}
		}

		return $next($request);
	}

	/**
	 * token 验证
	 */
	private function tokenVerify($token){
		if($token == ''){
			return false;
		}

		$token = substr($token, 7);

		if(empty($token)){
			return false;
		}

		$payload = TokenService::verifyToken($token);
		$admin = null;

		if($payload && !empty($payload['uid'])){
			$id = absint($payload['uid']);

			if($id){
				$admin = AdminModel::getById($id);
			}

			if(!empty($admin)){
				return $admin;
			}
		}

		return false;
	}
}