<?php
namespace app\client\repository;

use syin\Repository;

class RoleRepository extends Repository {
	public function model(){
		return 'app\client\model\Role';
	}

	/**
	 * 获取角色对应的权限禁止名单
	 *
	 * @param int/array $id 角色id
	 *
	 */
	public function getBlocklist($id){
		if(!is_array($id)){
			$id = [$id];
		}

		$results = $this->query
			->where('id', 'in', $id)
			->with('blocklist')
			->select();

		return $results;
	}
}