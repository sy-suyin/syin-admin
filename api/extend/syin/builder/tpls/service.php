<?php
namespace app\client\service;

use app\common\library\BaseService;
use app\common\library\RuntimeError;

class RoleService extends BaseService {

	/**
	 * 检查提交的数据
	 */
	public static function checkRequest($model = null) {
		$is_edit = is_null($model) ? false : true;
		$fields = [
			'name',
			'description' => 'desc',
		];

		$validation = [
			'rules' => [
				'name'      	=> 'require|unique:admin_role',
			],
			'msgs'  => [
				'name.require' 	 => '请先输入角色名称',
				'name.unique' 	 => '名称已被占用'
			]
		];

		// 数据筛选过滤
		$args = self::filterParmas($fields, $_POST);

		// 验证参数
		$is_edit && $args['id'] = $model->id;
		self::validate($args, $validation['rules'], $validation['msgs']);
		
		return $args;
	}
}