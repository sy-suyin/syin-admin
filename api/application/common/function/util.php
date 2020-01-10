<?php
/** 
 * 自定义公共函数库
 * 
 * 此函数文件中包含常用辅助/操作方法
 */



/** 
 * 发送短信验证码
 * 
 * @param int 	  $tpl_id	模板id
 * 	
 * 	   $tpls = array(
 *     )
 * 
 * @param string  $mobile 	接受短信号码
 * @param array   $data		发送的短信数据, 以键值对形式
 * 		
 */
function send_sms($tpl_id, $mobile, $data = array()){
	$url = 'http://v.juhe.cn/sms/send';
	$key = '';
	$tpl_val = arrry();

	static $tpls = array(
	);

	if(!isset($tpls[$tpl_id])){
		return new \app\common\library\RuntimeError('短信模板不存在');
	}

	$tpl_id = $tpls[$tpl_id];

	if(!empty($data)){
		foreach($data as $k => $v){
			$tpl_val[] = '#'.$k.'#='.$v;
		}

		$tpl_val = implode('&', $tpl_val);
	}
	
	$config = array(
		'key'   	=> $key,
		'mobile'    => $mobile,
		'tpl_id'    => $tpl_id, 				
		'tpl_value' => $tpl_val
	);

	$content = post_data_by_curl($url, $config);

	if($content){
		$result = json_decode($content,true);

		if(!empty($result['error_code'])){
			return new \app\common\library\RuntimeError($result['reason']);
		}else{
			return true;
		}
	}else{
		return new \app\common\library\RuntimeError('请求发送短信失败');
	}
}

//----------------------------------------------------------------------

/**
 * 获取微信access_token
 *
 * @param string $appid,	appid
 * @param string $secret,	appsecret
 *
 * return mixed
 */
function get_weixin_access_token($appid, $secret){
	$path	= RUNTIME_PATH.'weixin/';
	$file	= $path.'access_token.php';
	$token	= '';
	$time = time();

	if(is_file($file)){
		$data = file_get_contents($file);
		$data = explode('####', $data, 3);

		if(!empty($data) && count($data) == 3 && $data[1] > $time)
			$token = !empty($data[2]) ? $data[2] : '';
	}

	if(empty($token)){
		$data = get_data_by_curl('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
		$data = json_decode($data, true);

		if(!empty($data) && !empty($data['access_token'])){
			$token = $data['access_token'];

			if(!is_dir($path)){
				mkdir($path, 0755, true);
			}

			file_put_contents($file, '<?php die("Access denied!"); ?>####'.($time + $data['expires_in'] - 1200).'####'.$token, LOCK_EX);
		}
	}

	return $token;
}

//----------------------------------------------------------------------

/**
 * 获取微信jsapi_ticket
 *
 * @param string $token,	access token
 *
 * @return mixed
 */
function get_weixin_jsapi_ticket($token){
	$path	= RUNTIME_PATH.'weixin/';
	$file	= $path.'jsapi_ticket.php';
	$ticket	= '';
	$time = time();

	if(is_file($file)){
		$data = file_get_contents($file);
		$data = explode('####', $data, 3);

		if(!empty($data) && count($data) == 3 && $data[1] > $time)
			$ticket = !empty($data[2]) ? $data[2] : '';
	}

	if(empty($ticket)){
		$data = get_data_by_curl('https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token='.$token);
		$data = json_decode($data, true);

		if(!empty($data) && !empty($data['ticket'])){
			$ticket = $data['ticket'];

			if(!is_dir($path))
				mkdir($path, 0755, true);

			file_put_contents($file, '<?php die("Access denied!"); ?>####'.($time + $data['expires_in'] - 1200).'####'.$ticket, LOCK_EX);
		}
	}

	return $ticket;
}

/** 
 * 发送小程序模板消息
 */
function send_tpl_msg($form_id, $tpl_id, $open_id, $data = array(), $uid = 0, $path = ''){
	static $tpl_ids = array(
		// 付款成功
		'1' => ''
	);

	if(!isset($tpl_ids[$tpl_id])){
		return false;
	}

	$tpl_id = $tpl_ids[$tpl_id];

	if(empty($open_id)){
		if($uid){
			return false;
		}

		$open_id = db('wechat_bind')->where('user_id', $uid)->where('is_default', 1)->value('open_id');

		if(empty($open_id)){
			return false;
		}
	}

	$weixin_config	= config('weixin');
	$token = get_weixin_access_token($weixin_config['appid'], $weixin_config['secret']);

	if(!$token){
		return false;
	}

	$data = array(
		'access_token'  => $token,	
		'touser' 		=> $open_id,	
		'template_id'	=> $tpl_id,
		'page'			=> $path,	
		'form_id'		=> $form_id,	
		'data'			=> $data
	);

	$data = json_encode($data);

	$result = post_data_by_curl('https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$token, $data);

	if(!empty($result)){
		$result = json_decode($result, true);

		if(!empty($result['errcode']) && $result['errcode'] == 200){
			return true;
		}
	}

	return false;
}

/** 
 * 获取字典目录
 */
function get_dict_dir(){
	static $dict_dir = array();

	if(!empty($dict_dir)){
		return $dict_dir;
	}else{
		$cache_dict_dir = \think\Cache::get('dict_dir');

		if(!empty($cache_dict_dir)){
			$dict_dir = $cache_dict_dir;
		}else{
			$dict_dir = db('dict')->where('is_deleted', 0)->column('key, id, name');
			$dict_dir = !empty($dict_dir) ? $dict_dir : [];
	
			\think\Cache::set('dict_dir', $dict_dir, 3600);
		}
		
		return $dict_dir;
	}
}

/** 
 * 获取字典指定目录下所有数据
 * 
 * @param string $lebel 查询目录
 */
function get_dict_body($label){
	$dir = get_dict_dir();
	static $dict = array();

	if(empty($dir) || !isset($dir[$label])){
		return false;
	}

	if(empty($dict)){
		$cache_dict = \think\Cache::get('dict');
		!empty($cache_dict) && $dict = $cache_dict;
	}

	if(isset($dict[$label])){
		return $dict[$label];
	}else{
		$data = db('dict_body')->where('dict_id', $dir[$label]['id'])->order('id desc')->column('id, data as name, description');

		if(!empty($data)){
			$dict[$label] = $data;
		}

		\think\Cache::set('dict', $dict, 3600);

		return $data;
	}
}

/**
 * 获取字典指定数据
 */
function get_dict_text($dir,$body_id){
	$dict_body = get_dict_body($dir);
	
	if(empty($dict_body) || !isset($dict_body[$body_id])){
		return '';
	}

	return $dict_body[$body_id];
}


function add_debug($key, $value){
	db('test')->insert(array(
		'key'   => $key,
		'value' => json_encode($value)
	));
}

function get_debug($key){
	$result = db('test')->where('key', $key)->find();

	!empty($result) && $result['value'] = json_decode($result['value']);
	return $result;
}