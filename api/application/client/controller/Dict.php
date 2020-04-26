<?php
namespace app\client\controller;

use app\common\controller\Client;
use app\client\library\DictTool;
use think\Request;

class Dict extends Client {

	/**
	 * 数据字典
	 */
	public function listAction(){
		$result	= DictTool::getDictResultsArgs();
		$num = input('num/d', 5);
		$results = $result['model']->paginate($num, false, ['query'=>$result['args']]);
		$results = $results->toArray();

		return show_success('', [
			'total' => $results['total'],
			'current_page' => $results['current_page'],
			'page_max' => ceil($results['total'] / $num),
			'page_num' => $num,
			'results'  => $results['data'],
		]);
	}

	/** 
	 * 获取字典内容
	 */
	public function dictdataAction(){
		$id = absint(input('id'));

		if(!$id){
			return show_error('未找到相关数据');
		}

		$result	= DictTool::getDictDataResultsArgs($id);
		$num = 5;
		$results = $result['model']->paginate($num, false, ['query'=>$result['args']]);
		$results = $results->toArray();

		return show_success('', [
			'total' => $results['total'],
			'current_page' => $results['current_page'],
			'page_max' => ceil($results['total'] / $num),
			'page_num' => $num,
			'results'  => $results['data'],
		]);

		return show_success('', $results ? $results : []);
	}

	/**
	 * 数据字典添加
	 */
	public function dataaddAction(Request $request){
		$model = DictTool::getDictDataArgs();

		if(is_error($model)){
			return show_error($model->getErrorMsg());
		}

		if(! $model->save())
			return show_error('新建失败，请稍后重试');

		$request->log = '管理员'.($request->admin->name).', 添加了数据字典内容';

		return show_success('已成功新建数据字典内容');
	}

	/** 
	 * 数据字典修改
	 */
	public function dataeditAction(Request $request){
		$data_id = absint(input('data_id'));

		if(!isset($_POST['token'])){
			return show_error('暂不允许直接访问');
		}

		if($data_id){
			$model = \app\client\model\DictData::find($data_id);
		}

		if(empty($model)){
			return show_error('未找到相关数据');
		}

		$dict_id = $model->dict_id;
		$model = DictTool::getDictDataArgs(true, $model);

		if(is_error($model)){
			return show_error($model->getErrorMsg());
		}

		if($model->dict_id != $dict_id){
			return show_error('未找到相关数据');
		}

		if(! $model->save())
			return show_error('新建失败，请稍后重试');

		$request->log = '管理员'.($request->admin->name).', 添加了数据字典内容';

		return show_success('已成功新建数据字典内容');
	}

	/** 
	 * 数据字典 - 删除
	 */
	public function datadelAction(){
		$id = absint(input('id'));

		if(!$id){
			return show_error('未找到相关数据');
		}

		$item = db('dict_data')->where('is_system', 0)->delete($id);

		if($item){
			return show_success('删除成功');
		}else{
			return show_error('删除失败');
		}
	}
}