<?php
namespace app\client\repository;

use syin\Repository;

class RoleRepository extends Repository {
	public function model(){
		return 'app\client\model\Role';
	}

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