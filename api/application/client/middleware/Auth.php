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
		$token = $request->header('token');
		$controller = strtolower($request->controller());
		$action = strtolower($request->action());
		$is_logged = false;

		// 刷新 token
		if($controller == 'index' && $action == 'refreshtoken'){
			return $next($request);
		}

		if($token != ''){
			$payload = TokenService::verifyToken($token);
			$admin = null;

			if($payload && !empty($payload['uid'])){
				$admin = AdminModel::getById($payload['uid']);

				if(!empty($admin)){
					$is_logged = true;
				}
			}
		}

		if($is_logged){
			$request->admin = $admin;

			$verify_res = AdminService::verifyPermission($admin, $controller, $action);

			if(false == $verify_res){
				return show_error('对不起，您没有权限执行该操作 1');
			}
		}else{
			// 未登录或token已过期
			$whitelist = config('auth.whitelist');

			if(empty($whitelist) || !isset($whitelist[$controller]) || !in_array($action, $whitelist[$controller])){
				return json('请在登陆后重试', 401);
			}
		}

		return $next($request);
	}
}