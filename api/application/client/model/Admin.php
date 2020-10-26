<?php
namespace app\client\model;

use think\model\concern\SoftDelete;
use think\Model;

class Admin extends Model
{
	use SoftDelete;

    // 定义时间戳字段名
    protected $createTime = 'add_time';
	protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 关联模型
     */
    public function relation(){
        return $this->hasMany('AdminRelation');
    }
}