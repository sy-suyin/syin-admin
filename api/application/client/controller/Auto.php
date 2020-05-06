<?php
namespace app\client\controller;

use app\common\controller\Client;
use think\Request;

class Auto extends Client {

	/**
	 * 获取相应计算结果
	 */
	public function getinfoAction(){
		
		// 存储计算结果
		$results = [];

		// 获取数据库连接
		$connect = db()->getConnection();

		// 获取数据表名
		$tables = $connect -> getTables(false);

		// 获取数据表前缀
		$config = $connect -> getConfig();
		$prefix = $config['prefix'];

		// 自动生成页面时忽略的数据表
		$ignore_tables = [
			'admin', 'admin_role', 'admin_role_ban', 'admin_role_relation', 'setting', 'system'
		];

		// 为忽略的数据表加上前缀
		foreach($ignore_tables as $key => $val){
			$ignore_tables[$key] = $prefix. $val;
		}

		foreach($tables as $table){
			if(in_array($table, $ignore_tables)){
				continue;
			}

			$fields = $connect -> getTableInfo($table, 'type');

			$results[$table] = [];

			foreach($fields as $field => $info){
				if($field == 'id'){
					continue;
				}

				if($info == 'text'){
					$args['type'] = 'text';
				}else{
					$info = explode(' ', $info);
					// $info_len = count($info);

					preg_match("/^([a-z]+)\(([0-9]*)\)$|^([a-z]+)$/", $info[0], $match);

					if($match[1] == ''){
						$args['type'] = $match[0];
					}else{
						$args['type'] = $match[1];
						$args['length'] = $match[2];
					}
				}

				$results[$table][$field] = $args;
			}
		}

		return show_success('', $results);
	}
}