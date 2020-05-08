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