const menus = [
	{
		name: '工作台',
		icon: '',
		controller: 'index',
		action: 'index',
		is_hidden: 0,
	},
	{
		name: '个人中心',
		icon: '',
		controller: 'index',
		action: 'profile',
		is_hidden: 0,
	},
	{
		name: '页面模板',
		icon: '',
		controller: 'demo',
		action: 'indexman',
		is_hidden: 0,
		children: [
			{
				name: '列表',
				icon: '',
				controller: 'demo',
				action: 'listman',
				is_hidden: 0,
				children: [
					{
						name: '一般列表',
						icon: '',
						controller: 'demo',
						action: 'list-basic',
						is_hidden: 0,
						children: []
					},
					{
						name: '筛选列表',
						icon: '',
						controller: 'demo',
						action: 'list-filter',
						is_hidden: 0,
						children: []
					},
					{
						name: '卡片列表',
						icon: '',
						controller: 'demo',
						action: 'list-card',
						is_hidden: 0,
						children: []
					},
				]
			},
			{
				name: '详情',
				icon: '',
				controller: 'demo',
				action: 'profileman',
				is_hidden: 1,
				children: [
					{
						name: '基础详情页',
						icon: '',
						controller: 'demo',
						action: 'profile-basic',
						is_hidden: 0,
						children: []
					},
					{
						name: '高级详情页',
						icon: '',
						controller: 'demo',
						action: 'profile-basic',
						is_hidden: 0,
						children: []
					}
				]
			},
			{
				name: '表单',
				icon: '',
				controller: 'demo',
				action: 'formman',
				is_hidden: 1,
				children: [
					{
						name: '基础表单',
						icon: '',
						controller: 'demo',
						action: 'form-basic',
						is_hidden: 0,
						children: []
					},
					{
						name: '高级表单',
						icon: '',
						controller: 'demo',
						action: 'form-advanced',
						is_hidden: 0,
						children: []
					},
				]
			},
			{
				name: '日历',
				icon: '',
				controller: 'demo',
				action: 'calendar',
				is_hidden: 0,
			},
			{
				name: '锁屏',
				icon: '',
				controller: 'demo',
				action: 'lock',
				is_hidden: 0,
			},
		]
	},
	{
		name: '异常页',
		icon: '',
		controller: 'abnormal',
		action: 'indeman',
		is_hidden: 0,
		children: [
			{
				name: '403',
				icon: '',
				controller: 'abnormal',
				action: '403',
				is_hidden: 0
			},
			{
				name: '404',
				icon: '',
				controller: 'abnormal',
				action: '404',
				is_hidden: 0
			},
			{
				name: '500',
				icon: '',
				controller: 'abnormal',
				action: '500',
				is_hidden: 0,
			}
		]
	},
];

export {
	menus
}