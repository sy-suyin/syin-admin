import { request } from '@/libs/api';

// 获取管理员数据
export const getAdmin = ( id ) => {
	return request({
		url: '/system/adminData',
		method: 'get',
		params: { id },
		dispose: true,
	})
}

// 添加管理员数据
export const addAdmin = ( args ) => {
	return request({
		url: '/system/adminadd',
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 修改管理员数据
export const editAdmin = ( args ) => {
	return request({
		url: '/system/adminedit',
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 获取角色数据
export const getRole = ( id ) => {
	return request({
		url: '/system/roleData',
		method: 'get',
		params: { id },
		dispose: true,
	})
}

// 添加角色数据
export const addRole = ( args ) => {
	return request({
		url: '/system/roleadd',
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 修改角色数据
export const editRole = ( args ) => {
	return request({
		url: '/system/roleedit',
		method: 'post',
		dispose: true,
		data: args,
	})
}

// 获取所有角色
export const getRoles = () => {
	return request({
		url: '/system/roleAll',
		method: 'get',
		dispose: true,
	})
}

// 获取权限数据
export const getAccessData = (id = 0) => {
	return request({
		url: '/system/getaccessdata',
		method: 'get',
		params: { id },
		dispose: true,
	})
}