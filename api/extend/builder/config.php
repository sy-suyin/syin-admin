<?php

return [

	'master'    => [
		'table' => '',
	],

	'items'     => [],

	// 构建者
    'builders'  => [
        [
			'class'  => \builder\builders\service\ControllerBuilder::class,
			'config' => [],
		],
		[
			'class'  => \builder\builders\service\ModelBuilder::class,
			'config' => [],
		],
		[
			'class'  => \builder\builders\service\RepositoryBuilder::class,
			'config' => [],
		],
		[
			'class'  => \builder\builders\service\ServiceBuilder::class,
			'config' => [],
        ]
    ]
];