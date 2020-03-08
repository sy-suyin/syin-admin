<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\client\library\SystemTool;
use app\common\library\BaseTool;
use think\Request;

class System extends Client {


	/**
	 * 添加角色
	 */
	public function roleaddAction(Request $request){

		if(! $request->isPost()){
			return show_error('请求失败，请稍后重试');
		}

		$result = SystemTool::getRoleArgs();
		if(is_error($result)){
			p($result);
			die;
			return show_error($result->getErrorMsg());
		}

		if(! $result['model']->save()){
			return show_error('添加失败，请稍后重试');
		}

		$bans = [];

		$role_id = $result['model']->id;

		$module = $request->module();

		if(!empty($result['data_forbid'])){
			foreach($result['data_forbid'] as $val){
				$val['module'] = $module;
				$val['role_id'] = $role_id;
				$bans[] = $val;
			}
		}

		if(!empty($result['page_forbid'])){
			foreach($result['page_forbid'] as $val){
				$val['module'] = $module;
				$val['role_id'] = $role_id;
				$bans[] = $val;
			}
		}

		if(!empty($bans)){
			db('admin_role_ban')->insertAll($bans);
		}

		// $request->log = '管理员'.($request->admin->name).', 添加了新角色'.$result['model']->name;
		return show_success('已成功添加角色');
	}

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