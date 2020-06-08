<?php
namespace app\client\model;

use app\common\model\Base;
use app\common\model\Validation;

class Admin extends Base{

	use Validation;

	protected $name = 'admin';

	protected function generateFields(){
		static $fields = [
			'default' => [
				'name' 	     => 'string',
				'login_name' => ['type' => 'string', 'name' => 'login/t'],
				'password' 	 => ['type' => 'string', 'name' => 'password/t'],
				'roles'		 => ['type' => 'array', 'name' => 'roles/d'],
			],
			'login'   => [
				'login_name' => ['type' => 'string', 'name' => 'login/t'],
				'password' 	 => ['type' => 'string', 'name' => 'password/t'],
			],
			'profile' => [
				'login_name' => ['type' => 'string', 'name' => 'login/t'],
				'name'		 => 'string',
				'password'	 => 'string',
				'confirmpwd' => 'string',
				'oldpwd'	 => 'string',
				'avatar'	 => 'string',
			]
		];

		return $fields;
	}

	protected function generateValidation(){
		$validation = [
			'rules' => [
				'login' => [
					'login_name'   	=> 'require',
					'password'   	=> function($value, $rule){
						return $this->checkPassword($value, $rule, 'login');
					},
				],
				'add' => [
					'name'      	=> 'require|unique:admin',
					'login_name'   	=> 'require',
					'password'   	=> function($value, $rule){
						return $this->checkPassword($value, $rule, 'add');
					},
					'roles'			=> 'array|min:1'
				],
				'edit' => [
					'name'      	=> 'require|unique:admin',
					'login_name'   	=> 'require',
					'password'   	=> function($value, $rule){
						return $this->checkPassword($value, $rule, 'edit');
					},
					'roles'			=> 'array|min:1'
				],
				'profile' => [
					'name'      	=> 'require|unique:admin',
					'login_name'   	=> 'require',
				]
			],
			'msgs'   => [
				'default' => [
					'name.require' 	 => '请先输入名称',
					'name.unique' 	 => '名称已被占用',
					'login_name.require' => '请先输入登录账号名称',
					'password.require'   => '登录密码不能为空',
					'roles.array'	 => '角色数据有误',
					'roles.min'	 	 => '请先选择角色',
				]
			]
		];

		return $validation;
	}

	/**
	 * 检查登录密码
	 */
	protected function checkPassword($value, $rule, $scene){
		if(trim($value) == ''){
			if($scene == 'edit'){
				return true;
			}

			return '登录密码不能为空';
		}

		if(!check_password_strength($value)){
			return '密码格式有误';
		}

		return true;
	}
}