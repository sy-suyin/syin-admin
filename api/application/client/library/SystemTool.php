<?php
namespace app\client\library;

use \app\common\library\RuntimeError;
use app\common\library\BaseTool;

class SystemTool extends BaseTool{
	
	/**
	 * 获取新增/编辑管理员的表单字段
	 *
	 * @param bool 	$is_edit	是否为编辑状态
	 * @param mixed $model		Model对象实例, 当为修改时传入
	 */
	public static function getAdminArgs($is_edit = false, $model = null){
		$validate = new \app\client\validate\AdminValidate;

		$args = self::getRequestParams(array(
			'name'	 	 => array('type'=>self::STRING_TYPE),
			'login_name' => array('name'=>'login', 'type'=>self::STRING_TYPE),
			'password'	 => array('type'=>self::STRING_TYPE),
		));
		
		$roles = self::input('roles', self::ARRAY_TYPE);

		$args['update_time'] = time();

		if($is_edit){
			if(empty($model)){
				return new RuntimeError('未找到相关数据');
			}

			$args['id'] = $model->id;
		}else{
			$model = new \app\client\model\Admin();
			$args['add_time'] = $args['update_time'];
			$args['avatar'] = '/static/common/imgs/avatar/'.mt_rand(1,110).'.png';
		}

		$validate_res = $validate->check($args);
		
		if(false === $validate_res){
			return new RuntimeError($validate->getError());
		}

		if($args['password'] != ''){
			if(!check_password_strength($args['password'])){
				return new RuntimeError('密码格式有误');
			}

			$args['password'] = generate_password_hash($args['password']);
		}else{
			if(!$is_edit){
				return new RuntimeError('请先输入登录密码');
			}

			unset($args['password']);
		}

		$roles = array_filter(array_map('absint', $roles));

		if(empty($roles)){
			return new RuntimeError('请先选择权限角色');
		}

		$role_selected = db('admin_role')->where('is_deleted', 0)->where('is_disabled', 0)->where('id', 'in', $roles)->column('id');

		if(empty($role_selected)){
			return new RuntimeError('请刷新后，再重新选择权限角色');
		}

		$roles = $role_selected;
		unset($role_selected);

		$model->data($args);

		return [
			'roles' => $roles,
			'model' => $model
		];
	}
	
	/**
	 * 获取角色列表参数
	 *
	 * @param bool 	$is_deleted		是否查询被删除的数据
	 */
	public static function getRoleResultsArgs($is_deleted = false){
		$model = new \app\client\model\Role();
		$keyword = urldecode(input('k', ''));
		$args = array();

		if($keyword){
			$model = $model->where('name', 'like', '%'.$keyword.'%');
			$args['k'] = $keyword;
		}

		$model = $model->where('is_deleted', $is_deleted)->order('id desc');

		\think\facade\View::share('keyword', $keyword);

		return array(
			'model' => $model,
			'args'  => $args
		);
	}

	/**
	 * 获取新增/编辑角色的表单字段
	 *
	 * @param bool 	$is_edit	是否为编辑状态
	 * @param mixed $model		Model对象实例, 当为修改时传入
	 */
	public static function getRoleArgs($is_edit = false, $model = null){
		$validate = new \app\client\validate\RoleValidate;

		$args = self::getRequestParams(array(
			'name'	 	 => array('type'=>self::STRING_TYPE),
			'description' => array('name'=>'desc', 'type'=>self::STRING_TYPE),
		));

		$data_forbid = self::input('data_forbid', self::ARRAY_TYPE);
		$page_forbid = self::input('page_forbid', self::ARRAY_TYPE);

		// 处理数据权限
		if(!empty($data_forbid)){
			$temp = [];

			foreach($data_forbid as $controller => $actions){
				$controller = self::stringFilter($controller, '');

				if($controller != ''){
					$set = []; // 判断是否存在相同名的控制器
					foreach($actions as $action){
						$action = self::stringFilter($action, '');

						if($action != '' && !isset($set[$action])){
							$set[$action] = 1;
							$temp[] = [
								'controller' => $controller,
								'action' 	 => $action,
								'type'		 => 1
							];
						}
					}
				}
			}
			$data_forbid = $temp;
			unset($temp);
		}

		// 处理页面权限
		if(!empty($page_forbid)){
			$temp = [];

			foreach($page_forbid as $controller => $actions){
				$controller = self::stringFilter($controller, '');

				if($controller != ''){
					$set = []; // 判断是否存在相同名的控制器
					foreach($actions as $action){
						$action = self::stringFilter($action, '');

						if($action != '' && !isset($set[$action])){
							$set[$action] = 1;
							$temp[] = [
								'controller' => $controller,
								'action' 	 => $action,
								'type'		 => 2
							];
						}
					}
				}
			}
			$page_forbid = $temp;
			unset($temp);
		}

		$args['update_time'] = time();

		if($is_edit){
			if(empty($model)){
				return new RuntimeError('未找到相关数据');
			}

			$args['id'] = $model->id;
		}else{
			$model = new \app\client\model\Role();
			$args['add_time'] = $args['update_time'];
		}

		$validate_res = $validate->check($args);

		if(false === $validate_res){
			return new RuntimeError($validate->getError());
		}

		$model->data($args);
		return array(
			'data_forbid' => $data_forbid,
			'page_forbid' => $page_forbid,
			'model' =>$model
		);
	}

}