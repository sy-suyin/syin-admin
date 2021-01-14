namespace app\api\controller;

use app\admin\repository\{$class_name}Repository;
use app\admin\service\{$class_name}Service;

class {$class_name} {

    /**
     * 列表页面
     * GET
     */
    public function index({$class_name}Repository $repository){
		$params = {$class_name}Service::{$func_name}ListParams();

		$result = $repository->paginate($params);

		return show_success('', $result);
    }

    /**
     * 获取创建数据前所需数据
     * GET
     */
    public function create({$class_name}Repository $repository){
        return show_success('查询成功');
    }

    /**
     * 创建数据
     * POST
     */
    public function save({$class_name}Repository $repository){
        $args   = {$class_name}Service::checkRequest();
		$result = $repository->create($args);

		if(! $result){
			return show_error('保存失败');
		}

		return show_success('已成功添加{$table}数据');
    }

    /**
     * 详情
     * GET
     */
    public function read({$class_name}Repository $repository){
        $result = {$class_name}Service::getRecord($repository);

		return show_success('查询成功', $result);
    }

    /**
     * 获取修改时所需数据
     * GET
     */
    public function edit({$class_name}Repository $repository){
        $result = {$class_name}Service::getRecord($repository);

		return show_success('查询成功', $result);
    }

    /**
     * 修改数据
     * PSOT
     */
    public function update({$class_name}Repository $repository){
        $model = {$class_name}Service::getRecord($repository);
		$args  = {$class_name}Service::checkRequest($model);

		$result = $repository->update($args, $model->id);

		if(! $result){
			return show_error('保存失败');
		}

		return show_success('已成功修改{$table}数据');
    }

    /**
     * 删除数据
     */
    public function delete({$class_name}Repository $repository){
        $result = {$class_name}Service::delete($repository, '{$table}');

		if($result['status']){
			request()->title = '删除{$table}';
			return show_success('操作成功, 共'.$result['msg']);
		}else{
			return show_error('操作失败：'.$result['msg']);
		}
    }
}