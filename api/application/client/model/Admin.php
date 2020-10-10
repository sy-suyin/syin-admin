<?php
namespace app\client\model;

use app\common\model\Base;
use think\model\concern\SoftDelete;

class Admin extends Base{
	
	use SoftDelete;

    // 定义时间戳字段名
    protected $createTime = 'add_time';
	protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

}