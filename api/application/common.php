<?php

//----------------------------------------------------------------------

/**
 * 清理缩略图
 *
 * @param string $filepath,	原始文件完整路径
 *
 * @return int,	清理的文件数量
 */
function clear_image_thumb($filepath){
	$info	= pathinfo($filepath);
	$files	= glob($info['dirname'].'/'.$info['filename'].'*.'.$info['extension']);
	$num	= 0;

	if(!empty($files)){
		foreach($files as $file){
			if(!is_file($file))
				continue;

			unlink($file);
			$num += 1;
		}
	}

	return $num;
}

//----------------------------------------------------------------------

/**
 * 获取图片url
 *
 * @param string	$path,		图片相对路径
 *
 * @return mixed
 */
function get_img_url($path){
	$base_path = env('root_path').'public/';
	if(!$path){
		return '';
	}
	
	// 当是完整路径时, 直接返回
	if(substr($path,0,4) == 'http'){
		return $path;
	}

	if(is_file($base_path.$path)){
		return request()->domain().'/'.$path;
	}

	if(is_file($base_path.'uploads'.'/'.$path)){
		return request()->domain().'/'.'uploads'.'/'.$path;
	}
	
	return '';
}

//----------------------------------------------------------------------

/**
 * 获取指定分类的所有子分类
 *
 * @param int		$pid,		指定需要获取子分类的父ID，特殊的设置为0将获取所有分类
 * @param array		$category,	提前查询到的所有分类
 * @param string	$field,		指定返回字段值
 *
 * @return array
 */
function get_cate_children($pid, $category, $field=''){
	$children = array();

	foreach($category as $k => $cate){
		if($cate['parent_id'] != $pid)
			continue;

		$children[] = (!empty($field) && isset($cate[$field])) ? $cate[$field] : $cate;

		unset($category[$k]);

		$tmp = get_cate_children($cate['id'], $category, $field);

		if(!empty($tmp))
			$children = array_merge($children, $tmp);
	}

	return $children;
}

/** 
 * 发送站内信
 */
function send_user_message($uid, $role='user', $title = '', $content=''){
	$add = db('message')->insertGetId(array(
		'role' => $role,
		'role_id' => $uid,
		'title'   => $title,
		'content' => $content,
		'is_read' => 0,
		'add_time'=> time(),
		'update_time'=> time(),
	));

	return $add ? true : false;
}

/** 
 * 加载函数文件
 * 
 * @param string $filename 函数文件名称 
 */
function load_func($filename){
	static $functions = array();

	$path = Env::get('app_path');

	$filename	= str_replace('\\', '/', $filename);
	$filename	= trim($filename, '/');

	if(stripos($filename, '.php') === false){
		$filename .= '.php';
	}

	if(strpos($filename, '/') === false){
		$filename = 'function/'.$filename;
	}else{
		$filename = $filename;
	}

	$hash = md5($filename);

	if(isset($functions[$hash])){
		return;
	}

	$dirname = request()->module() .'/';
	if(!is_file($path.$dirname.$filename)){
		$dirname = 'common/';

		if(!is_file($path.$dirname.$filename)){
			return;
		}
	}

	$functions[$hash] = include($path.$dirname.$filename);
}

//----------------------------------------------------------------------

/**
 * 调试函数
 *
 * @param mixed	$data,	要输出的数据
 */
function p($data){
	if(!headers_sent()){
		header("Content-type: text/html; charset=utf-8");
	}

	// 定义样式
	$str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
	// 如果是boolean或者null直接显示文字；否则print
	if (is_bool($data)) {
		$show_data=$data ? 'true' : 'false';
	}elseif (is_null($data)) {
		$show_data='null';
	}else{
		$show_data=print_r($data,true);
	}
	$str.=$show_data;
	$str.='</pre>';
	echo $str;
}

//----------------------------------------------------------------------

/**
 * 判断是否为自定义错误
 *
 * @param mixed $var, 需要判断的变量，一般为调用程序的返回值
 *
 * @return bool
 */
function is_error($var){
	if(!is_object($var))
		return false;

	return $var instanceof \app\common\library\RuntimeError;
}

/** 
 * 添加系统日志
 */
function add_system_log(){
	
}