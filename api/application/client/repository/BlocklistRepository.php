<?php
namespace app\client\repository;

use syin\Repository;

class BlocklistRepository extends Repository {
	public function model(){
		return 'app\client\model\Blocklist';
	}
}