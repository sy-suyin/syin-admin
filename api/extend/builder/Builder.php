<?php
/**
 * 代码自动生成
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace builder;

use builder\control\Controller;
use builder\data\Master as DataMaster;

class Builder{

	public function build($table, $config = null){
		$default = include('config.php');
		if(is_array($table)){
			$config = $table;
			if(empty($config['builders'])){
				$config['builders'] = $default['builders'];
			}
		} else if(is_string($table)){
			$config = $default;
			$config['master']['table'] = $table;
		}

		// 处理默认配置

		// 处理数据
		$data_master = new DataMaster();
		$config = $data_master->parse($config);

		// 通过构造者调度器, 构建代码
		$builder = new Controller($config);
		$builder->build();
	}
}