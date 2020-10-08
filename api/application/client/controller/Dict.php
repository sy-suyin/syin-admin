<?php
namespace app\client\controller;

use app\client\repository\DictRepository;
use app\client\repository\DictDataRepository;
use app\client\service\DictService;
use think\Request;

class Dict {

	/**
	 * 数据字典
	 */
	public function index(DictRepository $repository){
		// 1. 获取查询参数
		$params = DictService::getListParams($_POST);

		// 2. 查询数据
		$result = $repository->paginate($params);

		return show_success('', $result);
	}

	/** 
	 * 获取字典内容
	 */
	public function dictData(DictDataRepository $repository){
		// 1. 获取查询参数
		$params = DictService::dictDataListParams($_POST);

		// 2. 查询数据
		$result = $repository->paginate($params);

		return show_success('', $result);
	}
}