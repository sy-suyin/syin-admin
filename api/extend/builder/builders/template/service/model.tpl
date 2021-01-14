namespace app\admin\model;

<?php if(!empty($is_sort_deleted)):?>
use think\model\concern\SoftDelete;
<?php endif;?>
use think\Model;

class {$class_name} extends Model
{
	<?php if(!empty($is_sort_deleted)):?>
use SoftDelete;
	<?php endif;?>

	protected $name = '{$table}';

	// 定义时间戳字段名
	protected $createTime = 'add_time';
	protected $updateTime = 'update_time';
	<?php if(!empty($is_sort_deleted)):?>
protected $deleteTime = 'delete_time';

	protected $defaultSoftDelete = 0;
	<?php endif;?>

}