<?php
namespace app\client\repository;

use syin\Repository;

class DictDataRepository extends Repository {
	public function model(){
		return 'app\client\model\DictData';
	}
}