import { request } from '@/libs/api';

export const login = ( {login, password} ) => {
	const data = {
		login,
		password
	}

	return request({
		url: '/login',
		method: 'post',
		dispose: true,
		data,
	})
}

export const updateProfile = (args) => {
	return request({
		url: '/index/profile',
		method: 'post',
		dispose: true,
		data: args,
	})
}

/**
 * 使用 token 获取用户信息
 * @param {*} args 
 */
export const tokenLogin = () => {
	return request({
		url: '/login/refresh',
		method: 'post',
		dispose: true,
	});
}