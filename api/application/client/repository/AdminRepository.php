<?php
namespace app\client\repository;

use syin\Repository;

class AdminRepository extends Repository {

    protected static $instance;

	public function model(){
		return 'app\client\model\Admin';
	}

	/**
	 * 获取关联的角色
	 */
	public function getRelationRoles($id){
		if(!is_array($id)){
			$id = [$id];
		}

		$results = $this->query
			->where('id', 'in', $id)
			->with('relation')
			->select();

		return $results;
	}
}