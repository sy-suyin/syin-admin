<?php
namespace app\client\controller;

use app\client\model\Dict as DictModel;
use app\client\model\DictData as DictDataModel;
use app\client\service\DictService;
use think\Request;

class Dict {

	/**
	 * 数据字典
	 */
	public function listAction(Request $request, DictModel $model){
		// 1. 获取查询参数
		$params = DictService::dictListParams($request->post());

		// 2. 查询数据
		$result = DictService::page($model, $params);

		return show_success('', $result);
	}

	/** 
	 * 获取字典内容
	 */
	public function dictDataAction(Request $request, DictDataModel $model){
		// 1. 获取查询参数
		$params = DictService::dictDataListParams($request->post());

		// 2. 查询数据
		$result = DictService::page($model, $params);

		return show_success('', $result);
	}
}