import api from '@/api/admin';
import { recycle } from '@/config/table';

let config = {
	// 请求链接
	urls: {
		del: api.del,
		dis: api.dis,
		sort: api.sort,
		list: api.index,
		recycle: api.recycle,
	},

	// 页面链接。 拆分能更好的区分, 能让请求链接和页面链接各不同
	pages: {
		add: '/admin/add',
		list: '/admin/index',
		edit: '/admin/eidt/:id',
		recycle: '/admin/recycle',
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
		}
	],
};

config = {...recycle, ...config};
export default config;