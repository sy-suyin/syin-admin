<?php
namespace app\client\repository;

use syin\Repository;

class RoleRepository extends Repository {
	public function model(){
		return 'app\client\model\Role';
	}
}