<?php

/** 
 * 内置文件上传处理
 */

namespace app\common\library;

use \app\common\library\RuntimeError;

class FileUpload{

	/** 
	 * 保存所有转移后的文件路径
	 */
	static $files = [];

	/** 
	 * 文件上传
	 */
	public function upload(){
		
	}

	/** 
	 * 将文件转移至正式文件夹
	 */
	public function transfer($files, $dir_name){
		$attachdir = env('root_path').'public/uploads/';
		$success = 0;
		$fail = 0;
		$success_files = [];

		foreach($files as $file){
			$p = stristr($file, $dir_name.'/');

			if($p !== $file){
				$dir = dirname($attachdir.$p);
				if(!is_dir($dir)){
					mkdir($dir, 0755, true);
				}

				if(!rename($attachdir.$file, $attachdir.$p)){
					$fail += 1;
					continue;
				}
			}

			$success += 1;
			$success_files[] = $p;
			self::$files[$p] = $file;
		}

		return [
			'success' => $success,
			'fail' => $fail,
			'success_files' => $success_files,
		];
	}

	/** 
	 * 将所有移动的文件还原至原处
	 */
	public function reset(){
		$attachdir = env('root_path').'public/uploads/';

		if(empty(self::$files)){
			return;
		}

		$files = self::$files;
		unset(self::$files);
		self::$files = [];

		foreach($files as $new => $origin){
			if(is_file($attachdir.$new)){
				remove($attachdir.$new, $attachdir.$origin);
			}
		}
	}
}