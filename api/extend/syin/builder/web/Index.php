<?php
namespace syin\builder\web;

use syin\builder\Base;

class Index extends Base{
	// public function

	public function build(){


		$path = env('ROOT_PATH') . 'extend/syin/builder/tpls/index.tpl';

		// 生成列表页面表格
		$html = app('view')->fetch($path, [
			'name' => $this->name,
			'class_name' => $this->class_name,
		]);

		// 生成列表页面所需的json数据
		$this->buildJs();
		// p($html);
	}

	protected function buildHtml(){

	}

	protected function buildJs(){
		$name = $this->name;
		$config = [
			'urls' => [
				'add' => "/{$name}/add",
				'del' => "/{$name}/del",
				'dis' => "/{$name}/dis",
				'edit' => "/{$name}/edit/:id",
				'list' => "/{$name}/index",
				'recycle' => "/{$name}/recycle",
			],

			'pages' => [
				'add' => "/{$name}/add",
				'edit' => "/{$name}/edit/:id",
				'list' => "/{$name}/list",
				'recycle' => "/{$name}/recycle",
			],

			'columns' => [
				[
					'type' => 'selection',
				],
				[
					'prop' => 'id',
					'label' => '编号',
					'width' => 60,
				],
				[
					'type' => 'switch',
					'label' => '状态',
					'field' => 'is_disabled',
					'handle' => 'disabled',
					'txt' => ['启用','禁用'],
					'color' => ['#13ce66', '#ff4949'],
					'access' => 'dis',
					'width' => 160,
				],
				[
					'prop' => 'add_time',
					'label' => '添加时间',
					'width' => 180,
				]
			],
		];

		$path = env('ROOT_PATH') . 'extend/syin/builder/web/tpls/list_js.tpl';

		// 生成列表页面表格
		$html = app('view')->fetch($path, [
			'name' => $this->name,
			'class_name' => $this->class_name,
			'config'	=> $config,
		]);

		// 存储目录为 runtime 目录
		$save_path = env('runtime_path'). 'builder/';
		$file_path = $save_path.$name.'.js';
		file_put_contents($file_path, $html);

		p($html);
	}
}