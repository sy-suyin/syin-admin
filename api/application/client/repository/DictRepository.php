<?php
namespace app\client\repository;

use syin\Repository;

class DictRepository extends Repository {
	public function model(){
		return 'app\client\model\Dict';
	}
}