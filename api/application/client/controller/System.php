<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\common\library\BaseTool;
use think\Request;

class System extends Client {


	/**
	 * 获取数据权限信息
	 */
	public function getaccessdataAction(){
		// 数据权限配置数据
		$config = config('access.');
		// 禁止访问数据权限
		$forbid = [];

		return show_success('', [
			'forbid' => $forbid,
			'config' => $config,
		]);
	}

}