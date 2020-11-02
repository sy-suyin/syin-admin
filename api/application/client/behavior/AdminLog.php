<?php
namespace app\client\behavior;

use app\client\repository\AdminLogRepository;

class AdminLog {
    public function run($params){
        if (request()->isPost()) {
            AdminLogRepository::record();
        }
    }
}