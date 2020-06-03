<?php
/** 
 * 客户端访问控制中间件
 */
namespace app\client\middleware;

use app\client\service\AdminService;
use app\client\service\TokenService;
use Firebase\JWT\JWT;

class Auth{

	public function handle($request, \Closure $next, $name){
		// 允许跨域
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers:x-requested-with,content-type,key,token');  
		header('Access-Control-Request-Method:GET,POST'); 
		header('Access-Control-Expose-Headers: token');
		if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS') exit;

		// 用户身份验证TOKEN
		$token = $request->header('token');
		$controller = strtolower($request->controller());
		$action = strtolower($request->action());
		$is_logged = false;

		if($controller == 'index' && $action == 'refreshtoken'){
			return $next($request);
		}else{
			if($token != ''){
				$payload = TokenService::verifyToken($token);
				$admin = null;
				
				if($payload && !empty($payload['uid'])){
					$admin = db('admin')
						->where('id', $payload['uid'])
						->where('is_disabled',0)
						->where('is_deleted',0)
						->find();
					
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
					return json('请在登陆后重试'.$token, 401);
				}
			}
	
			return $next($request);
		}

		/* 
		$token = TokenService::generateToken([
			'id' => 1
		]);

		p($token);

		$payload = TokenService::verifyToken($token);
		p($payload);
		die;
		
		die;

		$token = TokenService::generateRefreshToken([
			'id' => 1
		]);

		p($token);

		$payload = TokenService::verifyRefreshToken($token);
		p($payload);
		die; */

		// 尝试生成Token
		// token 使用 rs256 生成
		// refersh_token 使用 hs256 生成
		// token 有效期, 5分钟
		// refersh_token 有效期 3 天
/* 
		$time = time();
		$payload = [
			// 过期时间
			'exp' => $time + 600,
			// 生效时间，在此之前是无效的
			'nbf' => $time,
			// 签发时间
			'iat' => $time,
			// 客户端id
			'client_id' => '',
			// 用户id
			'uid' => ''
		];

		$path = env('root_path');
		$pri_key_path = $path. 'rsa/' . config('auth.refresh_token_pub');
		$privateKey = file_get_contents($pri_key_path);

		$pub_key_path = $path. 'rsa/' . config('auth.refresh_token_pri');
		$publicKey = file_get_contents($pub_key_path);

		$token = JWT::encode($payload, $privateKey, 'RS256');
		p($token); */
		/* 
		try{
			$payload = JWT::decode($token, $publicKey, ['RS256']);
			if(! is_array($payload)){
				$payload = json_decode(json_encode($payload),true);
			}
		}catch(\Exception $e){
			p($e);
		}
		p($payload);

		die; */
	}
}