namespace app\admin\service;

use app\common\library\BaseService;
use app\common\library\RuntimeError;

class {$class_name}Service extends BaseService {

	/**
	 * 获取{$table_name}列表查询所需条件
	 *
	 */
	public static function {$func_name}ListParams(){
		$where  = [];

		$params = self::getListParams($where);
		$params['order'] = ['id desc'];
		return $params;
	}

	/**
	 * 检查提交的数据
	 */
	public static function checkRequest($model = null) {
		$is_edit = is_null($model) ? false : true;

        $fields = <?php echo !empty($fields) ? var_export($fields) : '[]'; ?>;

        $rules = <?php echo !empty($rules) ? var_export($rules) : '[]'; ?>;

		// 数据筛选过滤
		$args = self::filterParmas($fields);

		// 验证参数
		$is_edit && $args['id'] = $model->id;
		self::validate($args, $rules);

		return $args;
	}
}