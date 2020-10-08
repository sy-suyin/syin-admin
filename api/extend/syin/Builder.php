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

class Builder{

	private $builders = [];

	public function build($table){
		// 先给各构建者初始化
		// $builders[] = new Controller();
		$builders[] = new Model($table);
		// $builders[] = new Service();

		$table_name = 'sy_'.$table;

		$sql =  $sql = "SELECT * FROM `information_schema`.`columns` "
		. "WHERE TABLE_SCHEMA = ? AND table_name = ? "
		. "ORDER BY ORDINAL_POSITION";
		$row = db()->query($sql, ['dashboard', $table_name]);

		foreach($row as $key => $val){
			// $data_type = $val['DATA_TYPE'];
			$element = $this -> generateInput($val);

			foreach($builders as $key => $builder){
				$builder->add($element);
			}
		}

		foreach($builders as $key => $builder){
			// $builder->print($element);
			$builder->build();
		}
	}

	protected function generateInput($v){
		$input_type = 'text';
		$data_type = $v['DATA_TYPE'];
		$params = [
			'comment' => $v['COLUMN_COMMENT'],
			'model'   => $v['COLUMN_NAME'],
		];

        switch ($data_type) {
            case 'bigint':
            case 'int':
            case 'mediumint':
            case 'smallint':
            case 'tinyint':
                $input_type = 'number';
                break;
			case 'enum':
                $input_type = 'select';
				$params['multi'] = true;
				break;
            case 'set':
				$input_type = 'select';
				$params['multi'] = false;
                break;
            case 'decimal':
            case 'double':
            case 'float':
				$input_type = 'number';
				$params['float'] = true;
                break;
            case 'longtext':
            case 'text':
            case 'mediumtext':
            case 'smalltext':
			case 'tinytext':
				$input_type = 'input';
				$params['textarea'] = true;
				break;
            default:
                break;
		}

		$suffix = explode('_', $v['COLUMN_NAME']);
		$suffix = end($suffix);

		// 判断是否为关联字段
		if($suffix == 'id'){
			$params['is_relation'] = true;

			// 此处还应能获取关联的表名 可能为多级
		}

		if($suffix == 'data'){
			if($data_type == 'enum'){
				$input_type = 'radio';
			} else if($data_type == 'set'){
				$input_type = 'checkbox';
			}
		}elseif($suffix == 'switch'){
			if($data_type == 'tinyint'){
				$input_type = 'switch';
			}
		}elseif($suffix == 'date'){
			if($data_type == 'int'){
				$input_type = 'date';
			}
		}elseif($suffix == 'time'){
			if($data_type == 'int'){
				$input_type = 'time';
			}
		}elseif($data_type == 'varchar'){
			if($suffix == 'color'){
				$input_type = 'color';
			}elseif($suffix == 'file'){
				$input_type = 'upload';
				$params['is_image'] = false;
				$params['multi'] = false;
			}elseif($suffix == 'files'){
				$input_type = 'upload';
				$params['is_image'] = false;
				$params['multi'] = true;
			}elseif($suffix == 'image' || $suffix == 'avatar'){
				$input_type = 'upload';
				$params['is_image'] = true;
				$params['multi'] = false;
			}elseif($suffix == 'images' || $suffix == 'avatars'){
				$input_type = 'upload';
				$params['is_image'] = true;
				$params['multi'] = true;
			}
		}

		$element = new Element($input_type, $params);
		// $input = FormItem::generate($input_type, $params);

		return $element;
	}
}