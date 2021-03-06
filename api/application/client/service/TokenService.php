<?php
namespace app\client\service;

use Firebase\JWT\JWT;

class TokenService {

	private const TOKEN_PRI_FILE = 'token_pri';
	private const TOKEN_PUB_FILE = 'token_pub';
	private const REFRESH_TOKEN_KEY = 'refresh_token_key';
	private const TOKEN_EXPIRE = 'token_expire';
	private const REFRESH_TOKEN_EXPIRE = 'refresh_token_expire';

	/**
	 * 生成token
	 */
	public static function generateToken($data = []){
		$time    = time();
		$expire  = config('auth.'. self::TOKEN_EXPIRE);
		$payload = [
			// 过期时间
			'exp' => $time + $expire,
			// 生效时间，在此之前是无效的
			'nbf' => $time,
			// 签发时间
			'iat' => $time,
			// 客户端id
			'client_id' => '',
			// 用户id
			'uid' => 0
		];

		$payload = array_merge($payload, $data);

		// 获取私钥数据
		$base_path    = env('root_path') . 'rsa/';
		$pri_key_path = $base_path . config('auth.'. self::TOKEN_PRI_FILE);
		$private_key  = file_get_contents($pri_key_path);

		$token = JWT::encode($payload, $private_key, 'RS256');
		return $token;
	}

	/**
	 * 生成 refresh token
	 */
	public static function generateRefreshToken($data = []){
		$time   = time();
		$expire = config('auth.'. self::REFRESH_TOKEN_EXPIRE);
		$refresh_key = config('auth.'. self::REFRESH_TOKEN_KEY);
		$payload = [
			// 过期时间
			'exp' => $time + $expire,
			// 生效时间，在此之前是无效的
			'nbf' => $time,
			// 签发时间
			'iat' => $time,
			// 客户端id
			'client_id' => '',
			// 用户id
			'uid' => 0
		];

		$payload = array_merge($payload, $data);

		// 附加上浏览器信息, 生成 hash
		$user_agent = request()->header('user-agent');
		$payload['hash'] = md5($user_agent.$refresh_key);

		$token = JWT::encode($payload, $refresh_key, 'HS256');
		return $token;
	}

	/**
	 * 验证 token
	 */
	public static function verifyToken($token){
		$base_path = env('root_path') . 'rsa/';
		$pub_key_path = $base_path . config('auth.'. self::TOKEN_PUB_FILE);
		$public_key = file_get_contents($pub_key_path);
		$curr_time = time();

		if(empty($token)){
			return false;
		}

		try{
			$payload = JWT::decode($token, $public_key, ['RS256']);
		}catch(\Exception $e){
			return false;
		}

		if(! is_array($payload)){
			$payload = json_decode(json_encode($payload), true);
		}

		// 验证时间
		if( $payload['iat'] > $curr_time
			|| $payload['iat'] > $curr_time
			|| $payload['exp'] < ($curr_time - 10)
		){
			return false;
		}

		// 使用 client_id, 判断是否存在过期记录

		return $payload;
	}

	/**
	 * 验证 refresh token
	 */
	public static function verifyRefreshToken($refresh_token){
		$refresh_key = config('auth.'. self::REFRESH_TOKEN_KEY);
		$curr_time = time();

		if(empty($refresh_token)){
			return false;
		}

		try{
			$payload = JWT::decode($refresh_token, $refresh_key, ['HS256']);
		}catch(\Exception $e){
			return false;
		}

		if(! is_array($payload)){
			$payload = json_decode(json_encode($payload),true);
		}

		// 验证时间
		if( $payload['iat'] > $curr_time
			|| $payload['iat'] > $curr_time
			|| $payload['exp'] < ($curr_time - 10)
		){
			return false;
		}

		// 验证hash
		$user_agent = request()->header('user-agent');
		$hash = md5($user_agent.$refresh_key);
		if($hash != $payload['hash']){
			return false;
		}

		return $payload;
	}
}