<?php
namespace syin\builder\service;

use syin\builder\Base;
use think\View;

class Model extends Base{
	// public function

	public function build(){
		// $view = new View();
		$path = env('ROOT_PATH') . 'extend/syin/builder/tpls/model.tpl';
		$html = app('view')->fetch($path, [
			'name' => $this->name
		]);

		p($html);
	}
}