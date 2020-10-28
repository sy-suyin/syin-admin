<?php
namespace syin\builder\service;

use syin\builder\Base;

class Service extends Base{

	public function build(){
		$path = env('ROOT_PATH') . 'extend/syin/builder/tpls/service.tpl';
		$html = app('view')->fetch($path, [
			'name' => $this->name,
			'class_name' => $this->class_name,
		]);

		// TODO: 需额外判断删除, 并根据删除进行处理, (及判断数据库是否有某个字段)

		p($html);
		die;
	}
}