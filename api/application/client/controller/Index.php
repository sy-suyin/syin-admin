<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\client\library\IndexTool;
use think\Request;

class Index extends Client {

	/** 
     * 个人中心
     */
    public function profileAction(Request $request){
		$admin = $request->admin;
		$model = IndexTool::getProfileArgs($admin);

		if(is_error($model)){
			return show_error($model->getErrorMsg());
		}

		if(! $model->save()){
			return show_error('操作失败，请稍后重试');
		}

		$request->admin = $request->admin->get($request->admin->id);

		// $request->log = '管理员'.($request->admin->name).', 修改了个人信息';
		return show_success('已成功修改项目');
    }
}