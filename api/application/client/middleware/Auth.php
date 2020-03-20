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

			$verify_res = \app\client\library\AdminTool::verifyPermission($admin, $request->controller, $request->admin);

			if(false == $verify_res){
				return show_error('对不起，您没有权限执行该操作');
			}
		}else{
			// TODO: 于此处判断用户访问的页面是否允许未登录直接访问
		}


        return $next($request);
	}
}