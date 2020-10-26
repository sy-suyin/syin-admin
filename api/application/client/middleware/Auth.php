<?php
/** 
 * 客户端访问控制中间件
 */
namespace app\client\middleware;

use app\client\repository\AdminRepository;
use app\client\service\Auth as AuthService;

class Auth{

	public function handle($request, \Closure $next, $name){
		// 用户身份验证TOKEN
		$token = $request->header('Authorization');
		$controller = strtolower($request->controller());
		$action = strtolower($request->action());
		$auth = AuthService::getInstance();
		$auth->setRepository(new AdminRepository);
		$request->auth = $auth;

		// 刷新 token
		if($controller == 'index' && $action == 'refreshtoken'){
			return $next($request);
		}
		// 重新尝试登录
		$auth->autologin($token);

		if(! $auth->isLogin()){
			// 未登录或token已过期
			$whitelist = config('auth.whitelist');

			if(empty($whitelist) || !isset($whitelist[$controller]) || !in_array($action, $whitelist[$controller])){
				return json('请在登陆后重试', 401)->header([
					'WWW-Authenticate' => 'Bearer, error="invalid_token", error_description="The access token expired"'
				]);
			}
		}else{
			// 权限验证
			if(false == $auth->check($controller, $action)){
				return show_error('对不起，您没有权限执行该操作');
			}
		}

		return $next($request);
	}
}