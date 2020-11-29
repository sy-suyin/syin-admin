import { request } from '@/libs/api';

const api = {
	login( {login, password} ){
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
	},
	
	updateProfile(args){
		return request({
			url: '/index/profile',
			method: 'post',
			dispose: true,
			data: args,
		})
	},
	
	/**
	 * 使用 token 获取用户信息
	 * @param {*} args 
	 */
	tokenLogin(){
		return request({
			url: '/login/refresh',
			method: 'post',
			dispose: true,
		});
	},
}

export default api;