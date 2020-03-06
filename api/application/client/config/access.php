<?php
// 后台数据访问权限配置

return [
	'system' => [
		'name' => '系统设置',
		'controller' => 'system',
		'action' => 'adminman',
		'children' => [
			[
				'name' => '管理员列表',
				'controller' => 'system',
				'action' => 'adminman',
				'children' => [
					[
						'name'  => '列表',
						'controller' => 'system',
						'action' => 'adminlist',
					],
					[
						'name'  => '添加',
						'controller' => 'system',
						'action' => 'adminlist',
					],
					[
						'name'  => '修改',
						'controller' => 'system',
						'action' => 'adminlist',
					],
				]
			],
			[
				'name' => '角色列表',
				'controller' => 'system',
				'action' => 'roleman',
				'children' => [
					[
						'name'  => '列表',
						'controller' => 'system',
						'action' => 'rolelist',
					],
					[
						'name'  => '添加',
						'controller' => 'system',
						'action' => 'rolelist',
					],
					[
						'name'  => '修改',
						'controller' => 'system',
						'action' => 'rolelist',
					],
				]
			],
		]
	]
];