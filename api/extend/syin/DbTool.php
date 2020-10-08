<?php
/**
 * 数据库工具
 *
 * @version 1.0.0
 */
namespace syin;

class DbTool{

	/**
	 * 备份
	 */
	public function backup(){

	}

	/**
	 * 还原
	 */
	public function restore(){

	}
	
	/**
	 * 逻辑删除项目
	 *
	 * @param Class    	$model 模型类实例
	 * @param String	$name  数据名称
	 */
	public static function deletedItemLogically($model, $name){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$deleted = absint(input('operate'));
		$operation_type = $deleted ? '删除' : '恢复';

		$result = $model->deletedItemLogically($id, $deleted);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => $result->getError()
			];
		}

		$msg = $operation_type.$result.'条'.$name.'记录';

		// 返回数据
		return [
			'status' => 1,
			'type'   => $operation_type,
			'result' => $result,
			'msg'    => $msg,
		];
	}

	/**
	 * 禁用项目
	 *
	 * @param Class    	$model 模型类实例
	 * @param String	$name  数据名称
	 *
	 */
	public static function disableItem($model, $name){
		$id = isset($_POST['id'])	?	$_POST['id']	:	array();
		$disabled = absint(input('operate'));
		$operation_type = $disabled ? '禁用' : '启用';

		$result = $model->disableItem($id, $disabled, ['is_admin' => 0]);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => $result->getError()
			];
		}

		// 返回数据
		$msg = $operation_type.$result.'条'.$name.'记录';
		return [
			'status' => 1,
			'type'   => $operation_type,
			'result' => $result,
			'msg'    => $msg,
		];
	}

	/**
	 * 项目自定义排序
	 *
	 * @param Class    	$model 模型类实例
	 * @param String	$name  数据名称
	 *
	 */
	public static function sortItem($model, $name){
		$data   = input('data/a');
		$result = $model->sortItem($data);

		if(is_error($result)){
			return [
				'status' => 0,
				'msg'	 => $result->getError()
			];
		}

		// 返回数据
		$msg = '对'.$result.'条'.$name.'记录进行排序';
		return [
			'status' => 1,
			'result' => $result,
			'msg'    => $msg,
		];
	}
}