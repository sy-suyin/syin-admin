import api from '@/api/admin';
import { list } from '@/config/table';

let config = {
	// 各跳转链接, 从各跳转连接中分析出请求的控制器及路由, 下面的如果需要控制权限, 只需要传入名称

	// 请求链接
	urls: {
		del: api.del,
		dis: api.dis,
		sort: api.sort,
		list: api.index,
		recycle: api.recycle,
	},

	// 页面链接
	pages: {
		add: '/admin/add',
		list: '/admin/index',
		edit: '/admin/edit/:id',
		recycle: '/admin/recycle',
	},

	columns: [
		{
			type: 'selection',
		},
		{
			type: 'edit',
			prop: 'sort',
			label: '排序',
			input: 'number',
			access: 'sort',
			width: 86,
			max: 99,
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
			type: 'switch',
			label: '状态',
			prop: 'is_disabled',
			handle: 'disabled',
			txt: ['启用','禁用'],
			color: ['#13ce66', '#ff4949'],
			access: 'dis',
			width: 160,
		},
		{
			type: 'label',
			prop: 'roles',
			label: '角色',
		},
		{
			prop: 'add_time',
			label: '添加时间',
			width: 160,
		}
	],

	filters: [
		{
			type: 'input',
			model: 'name',
			params: {
				label: '管理名称',
				show_label: true,
			},
			attrs: {
				placeholder: '请输入管理员名称', 
				size: 'mini',
			},
		},
		{
			type: 'date',
			model: 'time',
			params: {
				label: '添加时间',
				show_label: true,
			},
			props: {
				placeholder: '选择日期时间',
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

config = {...list, ...config};
export default config;