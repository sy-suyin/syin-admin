import axios from 'axios'
import store from '@/vuex/store'
import Observer from '@/libs/Observer.js';

class Token {

	static is_request = false;

	/**
	 * 获取 Token
	 */
	static getToken(){
		let old_auth_time = store.getters['auth/auth_time'];
		let auth_time = localStorage.getItem('auth_time');

		// 其他标签页已退出登录
		if(old_auth_time && auth_time == null){
			return store.commit('auth/logout');
		}

		// 本地存储的token已被更新
		if(old_auth_time != auth_time){
			// 仅当本地存储数据比vuex中数据新时, 才会更新vuex中的数据
			if(old_auth_time < auth_time){
				// 检查登录时间
				let old_login_time = store.getters['auth/login_time'];
				let login_time = localStorage.getItem('login_time');

				// 其他标签页已变更登录信息
				if(old_login_time < login_time){
					store.commit('auth/reload');
				}else{
					store.commit('auth/reloadToken');
				}
			}
		}

		return store.getters['auth/token'];
	}

	/**
	 * 更新token
	 * 
	 * @param {string} token 
	 */
	static updateToken(token){
		store.commit('auth/updateToken', token);
	}

	static getRefreshTokenUrl(){
		return store.getters['auth/refresh_token_url'];
	}

	/**
	 * 获取 refreshToken
	 */
	static getRefreshToken(){
		return store.getters['auth/refresh_token'];
	}

	/**
	 * 刷新token
	 */
	static refreshToken(){
		return new Promise((resolve, reject) => {
			if(this.isRequest()){
				Observer.on('refresh_token_end', (token)=>{
					resolve(token);
				});
			}else{
				this.is_request = true;
				let url = this.getRefreshTokenUrl();
				let refresh_token = this.getRefreshToken();
				
				axios.post(url, {refresh_token}).then(res => {
					res = res.data;

					// 此处判断后端传过来的数据有没有问题
					if(!res
						|| typeof(res.status) == 'undefined' 
						|| res.status != 1
					){
						reject(new Error('token获取失败'));
					}

					// 保存提交的数据
					let token = res.result.token || '';

					if(! token){
						reject(new Error('token获取失败'));
					}

					this.updateToken(token);

					// 通知其他地方可以继续执行
					Observer.emit('refresh_token_end', token);
					this.is_request = false;

					// 移除监听事件
					Observer.remove('refresh_token_end');
					// 返回数据
					resolve(token);
				}).catch(e => {
					reject(e);
				});
			}
		});
	}

	static isRequest(){
		return this.is_request;
	}
}

export default Token;