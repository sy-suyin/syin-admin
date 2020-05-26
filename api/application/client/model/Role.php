<?php
namespace app\client\model;

use app\common\model\Base;
use app\common\model\Validation;

class Role extends Base{

	use Validation;

	protected $name = 'admin_role';

	protected function generateFields(){
		$fields = [
			'default' => [
				'name' 	 => 'string',
				'description' => ['type' => 'string', 'name' => 'desc'],
			]
		];

		return $fields;
	}

	protected function generateValidation(){
		$validation = [
			'rules' => [
				'default' => [
					'name'   =>  'require|unique:admin_role',
				],
			],
			'msgs'   => [
				'default' => [
					'name.require' 	 => '请先输入角色名称',
					'name.unique' 	 => '名称已被占用'
				]
			]
		];

		return $validation;
	}

	/**
	 * 获取所有数据
	 */
	public static function getAll(){
		$results = self::field('id, name, description')
			->where('is_disabled', 0)
			->where('is_deleted', 0)
			->select()
			->toArray();

		return $results;
	}
}