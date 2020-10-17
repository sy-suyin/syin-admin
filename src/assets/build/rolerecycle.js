import { recycle } from '@/config/table';

let config = {
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
			type: 'selection',
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
}

config = {...recycle, ...config};
export default config;