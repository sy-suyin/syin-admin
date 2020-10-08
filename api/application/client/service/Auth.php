<?php
namespace app\client\service;

use app\client\repository\AdminRepository;
use app\common\library\BaseService;
use app\common\library\RuntimeError;

class Auth extends BaseService{

	/**
	 * 登录
	 */
	public static function login($type = 'admin'){
		$fields = [
			'login',
			'password',
			'capate'
		];

		// 数据筛选过滤
		$args = self::filterParmas($fields, $_POST);

		if(empty($args)){
			throw new RuntimeError('');
		}

		// 验证码
		// if(config('')){
		// 	if(! self::validateCapate($args['capate'])){

		// 	}
		// }

		$repository = new AdminRepository();

		// 登录账号
		if($args['login'] == '' || $args['password'] == ''
		//  || check_password_strength($args['password'])
		){
			throw new RuntimeError('');
		}

		$model = $repository->findBy('login_name', $args['login']);

		if(empty($model)){
			throw new RuntimeError('');
		}

		// 登录密码
		if(! check_password_hash($args['password'], $model->password)){
			throw new RuntimeError('');
		}

		if($model->is_disabled > 0){
			return new RuntimeError('登录账号已被禁用, 请联系管理');
		}

		return $model;
	}

	/**e
	 * 
	 */
	public static function authLogin(){

	}

	/**
	 * 
	 */
	public static function validateCapate($capate){

	}

	
	/**
	 * 生成token
	 */
	public static function generateToken($admin){
		// 生成token
		$result['access_token'] = TokenService::generateToken([
			'uid' => $admin->id
		]);

		$result['refresh_token'] = TokenService::generateRefreshToken([
			'uid' => $admin->id
		]);

		$result['refresh_token_url'] = url('index/refreshtoken', '', true, true);

		$result['token_expire'] = self::getTokenExpire();

		return $result;
	}

	/**
	 * 获取token有效时间
	 */
	public static function getTokenExpire(){
		return config('auth. token_expire');
	}
}