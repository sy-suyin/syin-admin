const menus = [
	{
		name: '工作台',
		icon: '',
		controller: 'index',
		action: 'index',
		is_hidden: 0,
		children: [
			
		]
	},
	{
		name: '个人中心',
		icon: '',
		controller: 'index',
		action: 'profile',
		is_hidden: 0,
		children: []
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
				is_hidden: 0,
				children: []
			},
			{
				name: '筛选',
				icon: '',
				controller: 'demo',
				action: 'filter',
				is_hidden: 0,
				children: []
			},
		]
	},
];

export {
	menus
}