<?php
/**
 * 代码自动生成
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace builder;

use builder\builders\Control;
use builder\builders\Master as BuilderMaster;
use builder\data\Master as DataMaster;
use builder\elements\Element;

class Builder{

	public function build($table, $config = []){
		if(is_array($table)){
			$config = $table;
		} else if(is_string($table)){
			$config['table'] = $table;
		}

		// 处理数据
		$data_master = new DataMaster($config);
		$data_master->parse($config);

		// 通过构造者调度器, 构建代码
		$builder = new BuilderMaster($config);
		$builder->build($data_master);
	}
}