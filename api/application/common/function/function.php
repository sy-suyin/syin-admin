<?php

/** 
 * 核心函数库
 */


/**
 * 生成用户密码HASH
 *
 * @param string	$str,		明文密码
 * @param int		$count,		HASH计算次数
 * @param string	$algo,		用来在散列密码时指示算法的密码算法常量
 *
 * @return string
 */
function generate_password_hash($str, $count=10, $algo=PASSWORD_DEFAULT){
	$options = [
		'cost' => $count,
	];

	return password_hash($str, $algo, $options);
}

//----------------------------------------------------------------------

/**
 * 检验由generate_password_hash方法生成的用户密码HASH
 *
 * @param string	$str,		明文密码
 * @param int		$hash,		HASH值
 *
 * @return bool
 */
function check_password_hash($str, $hash){
	return password_verify($str, $hash);
}

//----------------------------------------------------------------------

/**
 * 密码强度检查
 *
 * @param int $strength,	强度级别取值为1-3：
 * 							1. 仅检查字符长度，可任意组合字符；
 * 							2. 需要符合长度要求，至少需要包含数字及大小写字母；
 * 							3. 需要符合长度要求，至少需要包含数字、大小写字母及特殊字符；
 * @param int $min,			最小密码长度
 * @param int $max, 		最大密码长度
 *
 * @return bool
 */
function check_password_strength($str, $strength=1, $min=6, $max=0){
	$str = trim($str);
	$len = strlen($str);

	if(!empty($min) && $len < $min)
		return false;

	if(!empty($max) && $len > $max)
		return false;

	if($strength > 1 && !preg_match('/([0-9]+)/', $str))
		return false;

	if($strength > 1 && !preg_match('/([a-z]+)/i', $str))
		return false;

	if($strength > 2 && !preg_match('/(\W+)/', $str))
		return false;

	return true;
}

//----------------------------------------------------------------------

/**
 * 设置登录COOKIE，用户登录时可调用此函数生成COOKIE
 *
 * @param mixed		$user,		当前登录的用户对象或id
 * @param string	$role,		用户类型（不带前缀的用户表名）：例如user为前台用户；admin为后台用户以此类推
 * @param bool		$remember,	是否启用“记住登录”功能
 *
 * @return bool, 成功返回true，失败返回false
 */
function set_login_cookie($user, $role='user', $remember=false){
	if(empty($user) || (!is_array($user) && !is_object($user) && !is_numeric($user))){
		return false;
	}

	$role = strtolower($role);

	if(is_numeric($user)){
		$user = db($role)
			->where('id',$user)
			->where('is_disabled',0)
			->where('is_deleted',0)
			->find();
	}

	if(empty($user)){
		return false;
	}

	if(!($expire = config('auth.'.$role.'_cookie_expire'))){
		$expire = 10000;
	}

	$expire	*= 60;

	// 有效时间
	$effective_time = $expire + 300;

	$expire	+= time() ;

	if(!($key = config('auth.'.$role.'_cookie_key'))){
		$key = '1%L9yD3fQRcWuE6n21Rx8^@3sPX1nJNI';
	}

	$key = hash_hmac('md5', $key, $role.'_cookie_key');
	$str = '';

	if(!empty($user['id'])){
		$str .= $user['id'];
	}elseif(!empty($user->id)){
		$str .= $user->id;
	}

	$str .= '|'.$expire;
	$hash	= hash_hmac('sha1', $str, md5($user['password']));
	$cookie = openssl_encrypt($str.'|'.$hash, 'DES-ECB', $key, 0);

	unset($key, $str, $hash);

	$login	= config('auth.'.$role.'_login_cookie');
	$auth	= config('auth.'.$role.'_auth_cookie');

	cookie($login, $cookie, $effective_time);

	// 记住登录状态
	if($remember){
		\think\Cookie::forever($auth, $cookie);
	}

	return true;
}

//----------------------------------------------------------------------

/**
 * 认证登录COOKIE，判断用户登录状态时可调用此函数
 *
 * @param string $role,	用户类型（不带前缀的用户表名）：例如user为前台用户；admin为后台用户以此类推
 *
 * @return bool, 成功返回用户对象，失败返回false
 */
function auth_login_cookie($role='user'){
	$role	= strtolower($role);
	$login	= config('auth.'.$role.'_login_cookie');
	$auth	= config('auth.'.$role.'_auth_cookie');

	$cookie	= cookie($login);

	if(empty($cookie))
		$cookie	= cookie($auth);

	if(empty($cookie))
		return false;

	unset($login, $auth);

	if(!($key = config('auth.'.$role.'_cookie_key'))){
		$key = '1%L9yD3fQRcWuE6n21Rx8^@3sPX1nJNI';
	}

	$key	= hash_hmac('md5', $key, $role.'_cookie_key');
	$cookie = openssl_decrypt($cookie, 'DES-ECB', $key, 0);

	$cookie	= explode('|', $cookie);

	if(!is_array($cookie) || count($cookie) != 3){
		clear_login_cookie($role);
		return false;
	}

	list($uid, $expire, $hash) = $cookie;

	unset($cookie);

	// 有效期验证
	$expire = $expired = (int)$expire;

	// 进行表单提交时允许一定范围的超时
	if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
		$expired = $expire + 300;

	if($expired < time() || empty($uid)){
		clear_login_cookie($role);
		return false;
	}

	// 用户验证
	// if(empty($user)){
		$user = model($role)->getById($uid);
	// }

	if(empty($user))
		return false;

	// HASH验证
	$hmac = hash_hmac('sha1', $uid.'|'.$expire, md5($user['password']));

	if(md5($hmac) !== md5($hash) || !empty($user['is_deleted']) || !empty($user['is_disabled'])){
		clear_login_cookie($role);
		return false;
	}

	return $user;
}

//----------------------------------------------------------------------

/**
 * 清除登录COOKIE，用户登出时可调用此函数用于删除COOKIE
 *
 * @param string $role, 用户角色（不带前缀的用户表名），例如user为前台用户；admin为后台用户以此类推
 *
 * @return bool
 */
function clear_login_cookie($role='user'){
	$role	= strtolower($role);
	$login	= config('auth.'.$role.'_login_cookie');
	$auth	= config('auth.'.$role.'_auth_cookie');

	cookie($login, null);
	cookie($auth, null);
	return true;
}

//----------------------------------------------------------------------

/**
 * 判断用户是否登录
 *
 * 如果用户处于登录状态时，函数会产生一个记录当前用户的全局变量：$CURRENT_USER
 *
 * @param string $role, 用户角色（不带前缀的用户表名），例如user为前台用户；admin为后台用户以此类推
 *
 * @return bool
 */
function check_user_logged($role='user'){
	$user = auth_login_cookie($role);

	if(empty($user))
		return false;

	$GLOBALS['CURRENT_'.strtoupper($role)] = $user;

	return true;
}

//----------------------------------------------------------------------

/**
 * 获取系统（后台）设置参数
 *
 * @param string $name,	参数名称
 * @param string $type,	参数类型：system为系统参数；custom为自定义参数
 *
 * return mixed
 */
function get_setting($name, $type='system'){
	static $_settings = array();

	if(isset($_settings[$type][$name]))
		return $_settings[$type][$name];

	$settings = db('setting')->where('type', $type)->select();

	if(!empty($settings)){
		foreach($settings as $setting){
			$_settings[$type][$setting['name']] = $setting['value'];
		}
	}

	return isset($_settings[$type][$name]) ? $_settings[$type][$name] : null;
}

//----------------------------------------------------------------------

/**
 * 通过curl获取远程数据
 *
 * @param string	$url,		请求url
 * @param bool		$header,	是否返回去相应头
 *
 * @return mixed
 */
function get_data_by_curl($url, $header=false){
	$curl	= curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, $header);
	curl_setopt($curl, CURLOPT_NOBODY, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	$data = curl_exec($curl);
	curl_close($curl);

	return $data;
}

//----------------------------------------------------------------------

/**
 * 通过curl POST数据到远程服务器
 *
 * @param string	$url,		远程url
 * @param mixed		$data,		需要发送的
 * @param bool		$header,	是否需要返回
 */
function post_data_by_curl($url, $data, $header=false){
	$curl	= curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, $header);
	curl_setopt($curl, CURLOPT_NOBODY, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$result = curl_exec($curl);
	curl_close($curl);

	return $result;
}

//----------------------------------------------------------------------

/**
 * 通过socket获取远程数据
 *
 * @param string $url,	需要获取数据的url
 *
 * @return mixed
 */
function get_data_by_socket($url, $header=false){
	if(!function_exists('socket_create'))
		return false;

	if(!($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)))
		return false;

	$url		= parse_url($url);
	$host		= isset($url['host'])		? $url['host']		: '';
	$port		= isset($url['port'])		? $url['port']		: 80;
	$path		= isset($url['path'])		? $url['path']		: '/';
	$param		= isset($url['query'])		? $url['query']		: '';
	$fragment	= isset($url['fragment'])	? $url['fragment']	: '';

	if($host === '')
		return false;

	$query	= '';
	$crlf	= "\r\n";

	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 15, 'usec' => 0));
	socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 15, 'usec' => 0));
	socket_set_block($socket);

	if(!socket_connect($socket, gethostbyname($host), $port))
		return false;

	$query .= 'GET '.$path.($param !== '' ? '?'.$param : '').($fragment !== '' ? '#'.$fragment : '').' HTTP/1.0'.$crlf;
	$query .= 'Host: '.$host.$crlf;
	$query .= 'Connection: Close'.$crlf;
	$query .= $crlf;

	if(socket_write($socket, $query, strlen($query)) === false)
		return false;

	$result	= '';
	$length	= 256;

	while(true){
		$bytes = socket_recv($socket, $buf, $length, MSG_WAITALL);

		if($bytes === false || $bytes === 0)
			break;

		$result .= $buf;
	}

	if(!$header){
		$result = explode($crlf.$crlf, $result, 2);
		$result = isset($result[1]) ? $result[1] : false;
	}

	return $result;
}

//----------------------------------------------------------------------

/**
 * 添加用户现金交易记录数据
 *
 * @param int		$uid,			发送目标用户ID
 * @param string	$role,			用户角色
 * @param float		$original,		变更前金额
 * @param float		$change,		变更金额
 * @param string	$type,			变更类型：0减少；1增加
 * @param string	$description,	备注说明
 *
 * @return mixed,	成功返回新ID，失败返回false
 */
function add_cash_log($uid, $role, $original, $change, $type, $description=''){
	$original		= round(floatval(abs($original)) * 100) / 100;
	$change			= round(floatval(abs($change)) * 100) / 100;

	$add = db('cash_log')->insertGetId(array(
		'role'				=> $role,
		'role_id'			=> $uid,
		'original'			=> $original,
		'change'			=> $change,
		'type'				=> $type,
		'description'		=> $description,
		'add_time'			=> time()
	));

	return !$add ? false : $add;
}

//----------------------------------------------------------------------

/**
 * 变更用户现金账户金额
 *
 * @param int		$uid,		用户ID
 * @param string	$role,		用户角色
 * @param float		$amount,	变更数额，负数为减少，正数为添加
 * @param string	$type,		账户类型，固定取值valid|invalid，可用余额或是锁定金额
 * @param bool		$log,		是否记录流水日志
 * @param string	$desc,		流水日志的备注说明
 *
 * @return mixed
 */
function change_cash_amount($uid, $role, $amount, $type, $log=true, $desc=''){
	$cash = db('cash')->where('role', $role)->where('role_id', $uid)->find();

	if($amount < 0 && (empty($cash) || $cash[$type] < abs($amount)))
		return new \app\common\library\RuntimeError('帐户余额不足');

	// 无账户则新增
	if(empty($cash)){
		$cash['valid'] = 0;

		$args = array(
			'role'		=> $role,
			'role_id'	=> $uid,
			'valid'		=> 0,
			'invalid'	=> 0
		);

		$args[$type] = $amount;
		$exe = db('cash')->insertGetId($args);
	}

	// 有账户则更新
	else{
		if($amount > 0){
			$exe = db('cash')->where('id', $cash['id'])->setInc($type, $amount);
		}else{
			$exe = db('cash')->where('id', $cash['id'])->setDec($type, abs($amount));
		}
	}

	if(!$exe)
		return new \app\common\library\RuntimeError('处理账户时异常');

	// 记录日志
	if($log)
		add_cash_log($uid, $role, $cash['valid'], abs($amount), ($amount < 0 ? 0 : 1), $desc);

	return true;
}

//----------------------------------------------------------------------

/**
 * 锁定用户资金，即将可用余额转入不可用资金池内
 *
 * @param int		$uid,		用户ID
 * @param string	$role,		用户角色
 * @param float		$amount,	变更数额
 *
 * @return mixed
 */
function lock_cash_amount($uid, $role, $amount){
	$cash = db('cash')->where('role', $role)->where('role_id', $uid)->find();

	if(empty($cash) || $cash['valid'] < $amount)
		return new \app\common\library\RuntimeError('帐户余额不足');

	$exe = db('cash')
		-> where('id', $cash['id'])
		-> where('valid', '>=', $amount)
		-> inc('invalid', $amount)
		-> dec('valid',$amount)
		-> update();

	return $exe ? true : false;
}

//----------------------------------------------------------------------

/**
 * 解锁用户资金，即从不可用资金池中还原金额到可用余额内
 *
 * @param int		$uid,		用户ID
 * @param string	$role,		用户角色
 * @param float		$amount,	变更数额
 *
 * @return mixed
 */
function unlock_cash_amount($uid, $role, $amount){
	$amount	= abs($amount);
	$cash	= db('cash') -> where('role', $role) -> where('role_id', $uid)->find();

	if(empty($cash) || $cash['invalid'] < $amount)
		return new \app\common\library\RuntimeError('帐户余额不足');

	$exe = db('cash')
		-> where('id', $cash['id'])
		-> where('invalid', '>=', $amount)
		-> inc('valid', $amount)
		-> dec('invalid', $amount)
		-> update();

	return $exe ? true : false;
}