export default{
	// 各跳转链接
	urls: {
		add: '/system/adminadd',
		del: '/system/admindel',
		dis: '/system/admindis',
		edit: '/system/adminedit/:id',
		list: '/system/adminlist',
		recycle: '/system/adminrecycle',
	},

	columns: [
		{
			prop: 'selection',
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
			prop: 'add_time',
			label: '添加时间',
			width: 160,
			formatter: this.filterTime
		}
	],

	actionbar: [
		{
			type: 'btn',
			name: '还原',
			target: 'del',
			access: ['system', 'admindel'],
			params: {
				operate: 0,
			}
		},
	],

	toolbar: [
		{
			type: 'btn',
			name: '刷新',
			target: 'reload',
			icon: "el-icon-refresh-left",
			color: 'primary',
		},
		{
			type: 'url',
			name: '列表',
			target: 'list',
			color: 'primary',
			icon: "el-icon-s-promotion",
			access: ['system', 'adminlist'],
		},
		{
			type: 'btn',
			name: '还原',
			target: 'del',
			color: 'success',
			icon: "el-icon-delete",
			access: ['system', 'admindel'],
			params: {
				operate: 0,
			}
		},
	]
};