namespace app\client\model;

use think\model\concern\SoftDelete;
use think\Model;

class {$class_name} extends Model
{
	use SoftDelete;

	protected $name = '{$name}';

	// 定义时间戳字段名
	protected $createTime = 'add_time';
	protected $updateTime = 'update_time';
	protected $deleteTime = 'delete_time';
	protected $defaultSoftDelete = 0;
}