<?php
namespace app\client\repository;

use syin\Repository;

class AdminRepository extends Repository {
	public function model(){
		return 'app\client\model\Admin';
	}
}