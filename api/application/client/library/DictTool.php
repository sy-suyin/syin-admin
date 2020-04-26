<?php
namespace app\client\library;

use \app\common\library\RuntimeError;
use app\common\library\BaseTool;

class DictTool extends BaseTool{

	/**
	 * 获取字典列表参数
	 *
	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function getDictResultsArgs(){
		$model = new \app\client\model\Dict();
		$args = array();

		$model = $model->where('is_deleted', 0)->order('id desc');

		return array(
			'model' => $model,
			'args'  => $args
		);
	}

	/**
	 * 获取字典数据列表参数
	 *
	 * @param int 	$id		对应字典id
	 * 
	 */
	public static function getDictDataResultsArgs($id){
		$model = new \app\client\model\DictData();
		$args = array();

		$model = $model->where('dict_id', $id)->order('sort asc, id desc');

		return array(
			'model' => $model,
			'args'  => $args
		);
	}

	/**
	 * 获取新增/编辑数据字典内容的表单字段
	 *
	 * @param bool 	$is_edit	是否为编辑状态
	 * @param mixed $model		Model对象实例, 当为修改时传入
	 */
	public static function getDictDataArgs($is_edit = false, $model = null){
		$validate = new \app\client\validate\DictDataValidate;
		$args = array(
			'data' => input('data'),
			'sort' => absint(input('sort')),
			'description' => input('description'),
			'update_time' => time()
		);

		$id = absint(input('id'));

		if($id){
			$directory = db('dict')->where('is_deleted', 0)->find($id);
		}

		if(empty($directory)){
			return new RuntimeError('未找到字典目录');
		}

		if($is_edit){
			if(empty($model)){
				return new RuntimeError('未找到相关数据');
			}

			$args['id'] = $model->id;
		}else{
			$model = new \app\client\model\DictData();

			$args['add_time'] = $args['update_time'];
		}

		$validate_res = $validate->check($args);

		if(false === $validate_res){
			return new RuntimeError($validate->getError());
		}

		$args['dict_id'] = $id;
		$args['sort'] || $args['sort'] = 99;

		$model->data($args);
		return $model;
	}
}