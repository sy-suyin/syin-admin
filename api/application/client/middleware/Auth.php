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
		if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS') exit; 

		// 对跨域来源做判断

        return $next($request);
	}
}