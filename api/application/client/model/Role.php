<?php
namespace app\client\model;

use think\model\concern\SoftDelete;
use think\Model;

class Role extends Model
{
    use SoftDelete;

    protected $name = 'admin_role';

    // 定义时间戳字段名
    protected $createTime = 'add_time';
	protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 关联模型
     */
    public function blocklist(){
        return $this->hasMany('RoleBlocklist', 'role_id');
    }
}