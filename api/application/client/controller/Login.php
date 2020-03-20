<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\common\library\BaseTool;
use think\Request;


class Login extends Client{

	public function __construct(){
		parent::__construct();
		
	}

	public function indexAction(Request $request){
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

		$request->admin = $result;
		$this->getForbidData();
		die;

		unset($result['password']);

		// TODO: 除用户信息外还应返回权限和路由黑名单

		return show_success('登录成功', array(
			'user' => $result
		));
	}
	
	/**
	 * 获取权限黑名单
	 */
	protected function getForbidData(){
		$admin_id = request()->admin['id'];

		// 获取关联角色id;
		$role_ids = db('admin_role_relation')->where('admin_id', $admin_id)->column('role_id');

		// 禁止访问数据权限
		$forbid = [
			'data_forbid' => [],
			'page_forbid' => [],
		];

		$forbid_data = db('admin_role_ban')->where('role_id', implode(',', $role_ids))->select();

		// 计算规则, 整合成多个对象的数据组, 仅当各权限名单中都有的才算禁止
		p($forbid_data);
		die;
		if(!empty($forbid_data)){
			foreach($forbid_data as $val){
				if($val['type'] == 1){
					$forbid['data_forbid'][] = $val;
				}elseif($val['type'] == 2){
					$forbid['page_forbid'][] = $val;
				}
			}
		}

		return show_success('', [
			'forbid' => $forbid,
			'config' => $config,
		]);

	}
}