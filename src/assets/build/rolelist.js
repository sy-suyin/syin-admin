import { list } from '@/config/table';

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
			type: 'switch',
			label: '状态',
			field: 'is_disabled',
			handle: 'disabled',
			txt: ['启用','禁用'],
			color: ['#13ce66', '#ff4949'],
			access: 'dis',
			width: 160,
		},
		{
			prop: 'add_time',
			label: '添加时间',
			width: 180,
		}
	],
}

config = {...list, ...config};
export default config;