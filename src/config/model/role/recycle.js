import api from '@/api/role';
import { recycle } from '@/config/table';

let config = {
	// 各跳转链接
	urls: {
		del: api.del,
		dis: api.dis,
		sort: api.sort,
		list: api.index,
		recycle: api.recycle,
	},

	pages: {
		add: '/role/add',
		edit: '/role/edit/:id',
		list: '/role/list',
		recycle: '/role/recycle',
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