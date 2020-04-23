<?php
namespace app\client\library;

use \app\common\library\RuntimeError;
use app\common\library\BaseTool;

class IndexTool extends BaseTool{

	/**
	 * 获取新增/编辑管理员的表单字段
	 *
	 * @param mixed $model		Model对象实例
	 */
	public static function getProfileArgs($model = null){
		$validate = new \app\client\validate\AdminValidate;

		$args = self::getRequestParams(array(
			'name'	 	 => array('type'=>self::STRING_TYPE),
			'login_name' => array('name'=>'login', 'type'=>self::STRING_TYPE),
		));
		
		$avatar = self::input('avatar', self::STRING_TYPE);
		$password = self::input('password', self::STRING_TYPE);
		$oldpwd = self::input('oldpwd', self::STRING_TYPE);
		$confirmpwd = self::input('confirmpwd', self::STRING_TYPE);
		$args['update_time'] = time();

		if(empty($model)){
			return new RuntimeError('未找到相关数据');
		}

		$args['id'] = $model->id;

		$validate_res = $validate->check($args);
		
		if(false === $validate_res){
			return new RuntimeError($validate->getError());
		}

		if(!empty($password)){
			if(!check_password_hash($oldpwd, $model->password)){
				return show_error('原始密码输入有误');
			}

			if($oldpwd == $password){
				return show_error('新密码不能与旧密码相同');
			}

			if($confirmpwd != $password){
				return show_error('确认密码输入有误');
			}

			if(!check_password_strength($password)){
				return new RuntimeError('密码格式有误');
			}

			$args['password'] = generate_password_hash($password);
		}

		if($avatar){
			$avatar = '/static/common/img/avatar/'.$avatar.'.png';

			if(is_file(env('root_path').'public/'.$avatar)){
				$args['avatar'] = $avatar;
			}
		}

		$model->data($args);

		return  $model;
	}
}