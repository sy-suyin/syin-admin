const menus = [
	{
		name: '工作台',
		icon: '',
		controller: 'index',
		action: 'index',
		is_hidden: 0,
		children: [
			{
				name: '列表',
				icon: '',
				controller: 'test',
				action: 'list',
				is_hidden: 0,
				children: []
			},
			{
				name: '表单',
				icon: '',
				controller: 'test',
				action: 'form',
				is_hidden: 1,
				children: []
			},
		]
	},
	{
		name: '个人中心',
		icon: '',
		controller: 'index',
		action: 'profile',
		is_hidden: 0,
		children: [
			{
				name: '列表',
				icon: '',
				controller: 'index',
				action: 'list',
				is_hidden: 0,
				children: []
			},
			{
				name: '表单',
				icon: '',
				controller: 'index',
				action: 'form',
				is_hidden: 1,
				children: []
			},
		]
	},
	{
		name: '页面模板',
		icon: '',
		controller: 'demo',
		action: 'index',
		is_hidden: 0,
		children: [
			{
				name: '列表',
				icon: '',
				controller: 'demo',
				action: 'list',
				is_hidden: 0,
				children: []
			},
			{
				name: '表单',
				icon: '',
				controller: 'demo',
				action: 'form',
				is_hidden: 1,
				children: []
			},
			{
				name: '筛选',
				icon: '',
				controller: 'demo',
				action: 'filter',
				is_hidden: 0,
				children: [
					{
						name: '3级-1',
						icon: '',
						controller: 'demo',
						action: 'lv31',
						is_hidden: 0,

						children: [
							{
								name: '4级-1',
								icon: '',
								controller: 'demo',
								action: 'lv41',
								is_hidden: 0,
								
							},
							{
								name: '4级-2',
								icon: '',
								controller: 'demo',
								action: 'lv42',
								is_hidden: 0,
							},
							{
								name: '4级-3',
								icon: '',
								controller: 'demo',
								action: 'lv43',
								is_hidden: 0,
							}
						]
					}
				]
			},
		]
	},
];

export {
	menus
}