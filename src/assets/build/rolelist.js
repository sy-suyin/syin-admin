export default{
	// 各跳转链接
	urls: {
		add: '/role/add',
		del: '/role/del',
		dis: '/role/dis',
		edit: '/role/edit/:id',
		list: '/role/index',
		recycle: '/role/recycle',
	},

	pages: {
		add: '/system/roleadd',
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
			prop: 'add_time',
			label: '添加时间',
			width: 180,
		}
	],

	actionbar: [
		{
			type: 'url',
			name: '修改',
			access: 'edit',
		},
		{
			type: 'btn',
			name: '删除',
			access: 'del',
			params: {
				operate: 1,
			}
		}
	],
}