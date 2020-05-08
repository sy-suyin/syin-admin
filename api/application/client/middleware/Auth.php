<?php
/** 
 * 客户端访问控制中间件
 */
namespace app\client\middleware;

use think\Response;

class Auth{

	public function handle($request, \Closure $next, $name){
		// 允许跨域
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers:x-requested-with,content-type,key,token');  
		header('Access-Control-Request-Method:GET,POST'); 
		header('Access-Control-Expose-Headers: token');
		if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS') exit;

		// 请求来源身份标识
		$key = $request->header('key');
		// 用户身份验证TOKEn
		$token = $request->header('token');
		$controller = strtolower($request->controller());
		$action = strtolower($request->action());
		$is_logged = false;

		if($token != ''){
			$admin = auth_login_token($token, 'admin');
			
			if(!empty($admin)){
				$is_logged = true;
			}
		}

		// 对跨域来源做判断
		if(empty($key)){
			return show_error('对不起，您没有权限执行该操作');
		}

		if($is_logged){
			$request->admin = $admin;

			$verify_res = \app\client\library\AdminTool::verifyPermission($admin, $controller, $action);

			if(false == $verify_res){
				return show_error('对不起，您没有权限执行该操作');
			}
		}else{
			// 未登录或token已过期
			$whitelist = config('auth.whitelist');

			if(empty($whitelist) || !isset($whitelist[$controller]) || !in_array($action, $whitelist[$controller])){
				return json('请在登陆后重试', 403);
			}
		}

        return $next($request);
	}
}