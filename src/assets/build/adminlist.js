module.exports = {
	// 各跳转链接, 从各跳转连接中分析出请求的控制器及路由, 下面的如果需要控制权限, 只需要传入名称

	// 请求链接
	urls: {
		del: '/admin/del',
		dis: '/admin/dis',
		sort: '/admin/sort',
		list: '/admin/index',
		recycle: '/admin/recycle',
	},

	// 页面链接。 拆分能更好的区分, 能让请求链接和页面链接各不同
	pages: {
		add: '/system/adminadd',
		list: '/system/adminlist',
		edit: '/system/adminedit/:id',
		recycle: '/system/adminrecycle',
	},

	columns: [
		{
			prop: 'selection',
		},
		{
			prop: 'slot',
			slot: 'sort',
			label: '排序',
		},
		{
			prop: 'id',
			label: '编号',
			width: 60,
		},
		{
			prop: 'name',
			label: '名称',
			width: 200,
		},
		{
			prop: 'login_name',
			label: '登录账号',
		},
		{
			prop: 'tag',
			label: '状态',
			field: 'is_disabled',
			class: 'disabled-btn',
			handle: 'disabled',
			txt: ['启用','禁用'],
			access: 'dis',
			width: 160,
		},
		{
			prop: 'slot',
			slot: 'roles',
			label: '角色',
		},
		{
			prop: 'add_time',
			label: '添加时间',
			width: 160,
			// formatter: this.filterTime
		}
	],

	actionbar: [
		{
			type: 'url',
			name: '修改',
			target: 'edit',
			access: 'edit',
		},
		{
			type: 'btn',
			name: '删除',
			target: 'del',
			access: 'del',
			params: {
				operate: 1,
			}
		},
	],

	filter_fields: [
		{
			type: 'input',
			model: 'name',
			params: {
				label: '管理名称',
				show_label: true,
			},
			attrs: {
				placeholder: '请输入管理员名称', 
			},
			props: {
				size: 'mini',
			}
		},
		{
			type: 'date',
			model: 'time',
			params: {
				label: '添加时间',
				show_label: true,
			},
			attrs: {
				placeholder: '选择日期时间', 
			},
			props: {
				type: 'datetime',
				size: 'mini',
				valueFormat: 'timestamp'
			}
		},
		{
			type: 'select',
			model: 'status',
			params: {
				label: '状态',
				show_label: true,
			},
			data: [
				{
					label: '--',
					value: 0,
				},
				{
					label: '禁用',
					value: 1,
				},
				{
					label: '启用',
					value: 2,
				},
			],
			props: {
				size: 'mini'
			}
		},
	],
};