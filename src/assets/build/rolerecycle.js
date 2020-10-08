export default{
	// 各跳转链接
	urls: {
		add: '/system/roleadd',
		del: '/system/roledel',
		dis: '/system/roledis',
		edit: '/system/roleedit/:id',
		list: '/system/rolelist',
		recycle: '/system/rolerecycle',
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
			label: '角色名称',
			width: 200,
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
			access: ['system', 'roledel'],
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
			access: ['system', 'rolelist'],
		},
		{
			type: 'btn',
			name: '还原',
			target: 'del',
			color: 'success',
			icon: "el-icon-delete",
			access: ['system', 'roledel'],
			params: {
				operate: 0,
			}
		},
	]
}