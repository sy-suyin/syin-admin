<?php
namespace app\client\model;

use app\common\model\Base;

class Role extends Base{

    // 定义时间戳字段名
    protected $createTime = 'add_time';
	protected $updateTime = 'update_time';

	protected $name = 'admin_role';
}