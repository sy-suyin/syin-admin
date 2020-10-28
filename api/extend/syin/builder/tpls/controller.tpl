namespace app\client\controller;

use app\client\repository\{$class_name}Repository;
use app\client\service\{$class_name}Service;
use think\Request;

class {$class_name}{

	/**
	 * 角色管理 - 列表
	 */
	public function index({$class_name}Repository $repository){
		// 1. 获取查询参数
		$params = {$class_name}Service::getListParams($_POST);

		// 2. 查询数据
		$result = $repository->paginate($params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 回收站
	 */
	public function recycle({$class_name}Repository $repository){
		// 1. 获取查询参数
		$params = {$class_name}Service::getListParams($_POST);

		// 2. 查询数据
		$result = $repository->withDeleted()->paginate($params);

		return show_success('', $result);
	}

	/**
	 * 角色管理 - 角色数据
	 */
	public function detail({$class_name}Repository $repository){
		$model = {$class_name}Service::getRecord($repository);

		return show_success('查询成功', $model->toArray());
	}

	/**
	 * 角色管理 - 添加
	 */
	public function add({$class_name}Repository $repository){
		// 获取提交数据
		$args = {$class_name}Service::checkRequest();

		// 保存数据
		$result = $repository->create($args);

		if(! $result){
			return show_error('保存失败');
		}

		// 保存日志

		// 返回消息
		return show_success('已成功添加角色');
	}

	/**
	 * 角色管理 - 修改
	 */
	public function edit({$class_name}Repository $repository){
		$model = {$class_name}Service::getRecord($repository, $_POST);

		// 获取提交数据
		$args = {$class_name}Service::checkRequest($model);

		// 保存数据
		$result = $repository->update($args, $model->id);

		if(! $result){
			return show_error('添加失败，请稍后重试');
		}

		// 保存日志

		// 返回消息
		return show_success('已成功修改角色');
	}

	/**
	 * 角色管理 - 删除
	 */
	public function del({$class_name}Repository $repository){
		$result = {$class_name}Service::delete($repository, '角色');

		if($result['status']){
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_error('操作失败：'.$result['msg']);
		}
	}

	/**
	 * 角色管理 - 禁用
	 */
	public function dis({$class_name}Repository $repository){
		$result = {$class_name}Service::disableItem($repository, '角色');
		return json($result);
	}

	/**
	 * 角色管理 - 排序
	 */
	public function sort({$class_name}Repository $repository){
		$result = {$class_name}Service::sortItem($repository, 'sort', '99');
		return json($result);
	}

	/**
	 * 获取所有角色数据
	 */
	public function all({$class_name}Repository $repository){
		$results = $repository->all();
		return show_success('', $results);
	}
}