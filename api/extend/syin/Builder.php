<?php
/**
 * 代码自动生成
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace syin;

use syin\builder\Element;
use syin\builder\service\Controller;
use syin\builder\service\Model;
use syin\builder\service\Service;
use syin\builder\web\Index;

class Builder{

	private $builders = [];

	public function build($table){
		// 先给各构建者初始化
		$builders[] = new Index($table);
		// $builders[] = new Controller($table);
		// $builders[] = new Model($table);
		// $builders[] = new Service();

		$table_name = 'sy_'.$table;

		$sql = "SELECT * FROM `information_schema`.`columns` "
			. "WHERE TABLE_SCHEMA = ? AND table_name = ? "
			. "ORDER BY ORDINAL_POSITION";

		$row = db()->query($sql, ['dashboard', $table_name]);

		foreach($row as $val){
			$element = new Element($val);

			foreach($builders as $builder){
				$builder->add($element);
			}
		}

		p('builder');

		foreach($builders as $key => $builder){
			$builder->build();
		}
	}
}