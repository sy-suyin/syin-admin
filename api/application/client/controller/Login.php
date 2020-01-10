<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\common\library\BaseTool;
use think\Request;


class Login extends Client{

	public function __construct(){
		parent::__construct();
		
	}

	public function indexAction(){
		$login_name = BaseTool::input('login', 0);
		$password = BaseTool::input('password', 0);

		if('' == trim($login_name)){
			return show_error('登录账号不能为空');
		}

		if('' == trim($password)){
			return show_error('登录密码不能为空');
		}

		if(!check_password_strength($password)){
			return show_error('登录密码格式有误');
		}

		$result = db('admin')
			->where('is_deleted', 0)
			->where('login_name', $login_name)
			->find();

		if(empty($result)){
			return show_error('登录账号或密码错误'.$login_name);
		}

		if(!check_password_hash($password, $result['password'])){
			return show_error('登录账号或密码错误');
		}

		if($result['is_disabled'] > 0){
			return show_error('登录账号已被禁用, 请联系管理');
		}
		
		set_login_cookie($result, 'admin');
		return show_success('登录成功', $result);
	}
}