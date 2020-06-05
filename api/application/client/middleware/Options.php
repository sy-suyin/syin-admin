<?php
/** 
 * 客户端访问控制中间件
 */
namespace app\client\middleware;

class Options{
	
	public function handle($request, \Closure $next, $name){
		// 允许跨域
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers:x-requested-with,content-type,Authorization');  
		header('Access-Control-Request-Method:GET,POST'); 
		header('Access-Control-Expose-Headers: Authorization, access_token, refresh_token, refresh_token_url, token_type, expires');
		header('Cache-Control: no-store');

		if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS') exit;

		return $next($request);
	}
}