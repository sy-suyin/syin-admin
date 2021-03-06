import { request } from '@/libs/api';

const api = {

	// 获取管理员数据
	getAdmin( id ){
		return request({
			url: '/admin/detail',
			method: 'get',
			params: { id },
			dispose: true,
		});
	},

	// 添加管理员数据
	addAdmin( args ){
		return request({
			url: '/admin/add',
			method: 'post',
			dispose: true,
			data: args,
		});
	},

	// 修改管理员数据
	editAdmin( args ){
		return request({
			url: '/admin/edit',
			method: 'post',
			dispose: true,
			data: args,
		});
	},

	// 获取角色数据
	getRole( id ){
		return request({
			url: '/role/detail',
			method: 'get',
			params: { id },
			dispose: true,
		});
	},

	// 添加角色数据
	addRole( args ){
		return request({
			url: '/role/add',
			method: 'post',
			dispose: true,
			data: args,
		});
	},

	// 修改角色数据
	editRole( args ){
		return request({
			url: '/role/edit',
			method: 'post',
			dispose: true,
			data: args,
		});
	},

	// 获取所有角色
	getRoles(){
		return request({
			url: '/role/all',
			method: 'get',
			dispose: true,
		});
	},

	// 获取权限数据
	getAccessData(id = 0){
		return request({
			url: '/system/getaccessdata',
			method: 'get',
			params: { id },
			dispose: true,
		});
	}
};

export default api;