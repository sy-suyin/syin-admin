<?php
/** 
 * 客户端访问控制中间件
 */
namespace app\client\middleware;

class Options{
	
	public function handle($request, \Closure $next, $name){
		// 允许跨域
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers:x-requested-with,content-type,token');  
		header('Access-Control-Request-Method:GET,POST'); 
		header('Access-Control-Expose-Headers: token');
		if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS') exit;

		return $next($request);
	}
}