<?php
namespace app\client\controller;

use app\client\repository\AdminRepository;
use app\client\repository\RoleRepository;
use app\client\service\SystemService;
use think\Request;

class System {

	/**
	 * 获取数据权限信息
	 */
	public function getAccessData(){
		// 角色id
		$id = absint(input('id'));

		// 数据权限配置数据
		$config = config('access.');

		// 获取角色的禁止权限信息
		$blocklist = SystemService::getRoleBlocklist($id, $config);

		return show_success('', [
			'blocklist' => $blocklist,
			'config' 	=> $config,
		]);
	}
}