<?php

load_func('function');
load_func('util');
load_func('format');

/**
 * 返回失败消息
 *
 * @param string	$msg,	消息
 * @param int		$code,	状态码
 *
 */
function show_error($msg, $code = 200){
	return message(array(
		'status' => 0,
		'msg'	 => $msg
	), $code);
}

/**
 * 返回成功消息
 *
 * @param string	$msg,	消息
 * @param mixed		$data,	要输出的数据
 */
function show_success($msg, $data=[]){
	return message(array(
		'status' => 1,
		'msg'	 => $msg,
		'result' => $data
	));
}

function message($return_data, $code = 200){
	return json($return_data, $code);
}

//----------------------------------------------------------------------

/**
 * 添加当前登录管理员操作日记
 *
 * @param string	$content,	简要描述
 * @param mixed		$data,		自定义需要存放的数据
 * @param int		$uid,		指定用户ID，默认获取当前登录的用户
 *
 * @return mixed,	成功返回新ID，失败返回false
 */
function add_operate_log($content, $data='', $type='admin', $uid=0){
	if(empty($uid) && !check_user_logged($type))
		return false;

	empty($uid) && $uid = $GLOBALS['CURRENT_'.strtoupper($type)]['id'];

	$add = db('log_operate')->insert(array(
		'type'			=> $type,
		'user_id'		=> $uid,
		'program'		=> MODULE_NAME,
		'controller'	=> CONTROLLER_NAME,
		'action'		=> ACTION_NAME,
		'content'		=> htmlspecialchars(strip_tags($content), ENT_QUOTES, 'UTF-8'),
		'data'			=> htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8'),
		'add_time'		=> time(),
		'client_ip'		=> request()->ip(),
		'user_agent'	=> request()->server()['HTTP_USER_AGENT']
	));

	return $add ? $add : false;
}

/**
 * 操作权限控制
 *
 * admin_ban表为权限黑名单，仅当菜单在权限黑名单中时，才不允许访问
 * 注: 每个管理可以同时绑定多个角色, 当页面在黑名单中的次数与绑定角色次数一直时, 才会不允许访问
 *
 * @param string $controller 控制器名称
 * @param string $action	 方法名称
 */
function admin_can_access($controller, $action){
	static $ban_list = [];

	$controller = strtolower($controller);
	$admin = request()->admin;

	if(empty($admin)){
		return false;
	}

	if($admin['is_admin']){
		return true;
	}

	if(! isset($ban_list[$admin['id']])){
		$accesses = [];

		$role_ids = db('admin_role_relation')
			->alias('rr')
			->join('admin_role r', 'r.id = rr.role_id', 'left')
			->where('rr.admin_id', $admin['id'])
			->column('r.id');

		if(!empty($role_ids)){
			$record = db('admin_ban')->where('role_id', 'in', $role_ids)->select();
			$role_count = count($role_ids);

			if(!empty($record)){
				$map = [];

				foreach($record as $val){
					if(! isset($map[$val['controller']])){
						$map[$val['controller']] = [];
					}

					if(! isset($map[$val['controller']][$val['action']])){
						$map[$val['controller']][$val['action']] = 0;
					}

					$map[$val['controller']][$val['action']] += 1;
				}

				foreach($map as $k_controller => $val){
					foreach($val as $k_action => $count){
						if($role_count == $count){
							$bans[$k_controller][$k_action] = 1;
						}
					}
				}
			}

			if(empty($bans)){
				return true;
			}

			$ban_list[$admin['id']] = $bans;
		}
	}

	if(isset($ban_list[$admin['id']][$controller][$action])){
		return false;
	}else{
		return true;
	}
}

//----------------------------------------------------------------------

/**
 * ip2long函数修正
 * 修正ip2long函数在特定系统环境下会产生负值的问题
 *
 * @param string $ip, ip地址
 * @return string
 */
function ip_to_long($ip){
	return sprintf('%u', ip2long($ip));
}

/**
 * 密码加密
 * 
 * @param string $password 需加密的密码
 * @param $salt 密码加密盐值
 * @param $key  密码加密键
 */
function password_encrypt($password, $salt='', $key='sy_site_password'){
	$secret  = config('app.password_secret');
	$key = hash_hmac('md5', $salt.md5($secret.'-'.$salt), $key);

	$password = openssl_encrypt($password, 'DES-ECB', $key, 0);
	return $password;
}

/**
 * 密码解密
 * 
 * @param string $password 需加密的密码
 * @param $salt 密码加密盐值
 * @param $key  密码加密键
 */
function password_decrypt($password, $salt='', $key='sy_site_password'){
	$secret  = config('app.password_secret');
	$key = hash_hmac('md5', $salt.md5($secret.'-'.$salt), $key);

	$password = openssl_decrypt($password, 'DES-ECB', $key, 0);
	return $password;
}