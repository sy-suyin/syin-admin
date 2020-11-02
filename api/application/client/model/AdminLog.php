<?php
namespace app\client\model;

use think\Model;

class AdminLog extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'add_time';
	protected $updateTime = '';
}