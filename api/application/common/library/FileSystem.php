<?php

/** 
 * 内置文件管理系统
 */

namespace app\common\library;

use \app\common\library\RuntimeError;

class FileSystem{

	/**
	 * 文件上传 
	 * 
	 * @param string $key 	$_FILES 数组键
	 */
	public function upload($key){
		// 如果文件存在, 则直接返回相关信息, 否则保存文件并在成功之后, 在数据库中添加一条记录
		
		$original = $_FILES[$key]['tmp_name'];
		$md5_hash = md5_file($original);
		$sha1_hash = sha1_file($original);
		$hash = '';

		$md5_hash && $hash .= $md5_hash;
		$sha1_hash && $hash .= $sha1_hash;

		$record = db('filesys')->where('hash', $hash)->find();

		if(empty($record)){
		}else{
			$ext = '';
			$file_name = $hash . '.' . $ext;
		}
	}

	/** 
	 * 检查文件是否存在
	 * 
	 * @param mixed $key	被查询记录的id或文件hash
	 */
	public function check($key){
		if(is_integer($key)){
			// 从记录中以id进行查询

			$record = db('filesys')->get($key);
		}else{
			// 从记录中以文件hash进行查询

			$record = db('filesys')->where('hash', $key)->find();
		}

		if(empty($record)){
			return false;
		}else{
			return $record['path'];
		}
	}

	/** 
	 * 获取文件url
	 * 
	 * @param int $id 		文件记录id
	 */
	public function get($id){
		$record = db('filesys')->get($id);
	}

	/** 
	 * 添加文件引用
	 * 
	 * @param int $id 	文件记录id
	 */
	public function use($id){

	}

	/** 
	 * 解除文件引用
	 * 
	 * @param int $id 	文件记录id
	 */
	public function remove($id){

	}

}