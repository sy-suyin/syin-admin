import { request } from '@/libs/api';

/** 
 * 链接合集
 * 格式: 方法名 => 链接
 */
const URLS = {
	getAdmin: '/system/admindetail',
	addAdmin:  '/system/adminadd',
	editAdmin: '/system/adminedit',
	getRole: '/system/roledetail',
	addRole:  '/system/roleadd',
	editRole: '/system/roleedit',
	getRoles: '/system/getallroles',
	getAccessData: '/system/getaccessdata',
};

// 获取管理员数据
export const getAdmin = ( id ) => {
	return request({
		url: URLS['getAdmin'],
		method: 'get',
		params: { id },
		dispose: true,
	})
}

// 添加管理员数据
export const addAdmin = ( args ) => {
	return request({
		url: URLS['addAdmin'],
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 修改管理员数据
export const editAdmin = ( args ) => {
	return request({
		url: URLS['editAdmin'],
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 获取角色数据
export const getRole = ( id ) => {
	return request({
		url: URLS['getRole'],
		method: 'get',
		params: { id },
		dispose: true,
	})
}

// 添加角色数据
export const addRole = ( args ) => {
	return request({
		url: URLS['addRole'],
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 修改角色数据
export const editRole = ( args ) => {
	return request({
		url: URLS['editRole'],
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 获取所有角色
export const getRoles = () => {
	return request({
		url: URLS['getRoles'],
		method: 'get',
		dispose: true,
	})
}

// 获取权限数据
export const getAccessData = (id = 0) => {
	return request({
		url: URLS['getAccessData'],
		method: 'get',
		params: { id },
		dispose: true,
	})
}