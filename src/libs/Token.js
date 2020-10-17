import axios from 'axios'
import store from '@/store';
import Observer from '@/libs/Observer';

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
					location.reload();
				}else{
					store.commit('auth/reloadToken');
				}
			}
		}

		let token = store.getters['auth/token'];
		return 'Bearer ' + token;
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
				let url = this.getRefreshTokenUrl();
				let refresh_token = this.getRefreshToken();

				if(! url || url.length < 2){
					reject(new Error('token 获取失败')); 
				}

				this.is_request = true;

				axios.post(url, {refresh_token}).then(response => {
					const result = response.data;
					const headers = response.headers;

					if(response.status !== 200){
						return Promise.reject(new Error('token 获取失败'));
					}

					// 此处判断后端传过来的数据有没有问题
					if(!result || typeof(result.status) == 'undefined' || result.status != 1){
						this.is_request = false;
						reject(new Error('token 获取失败'));
					}

					// 获取并保存 token
					if(! headers.hasOwnProperty('token_type') || ! headers.hasOwnProperty('access_token')){
						this.is_request = false;
						reject(new Error('token 获取失败'));
					}

					if(! headers['access_token'] || headers['token_type'] != 'bearer'){
						this.is_request = false;
						reject(new Error('token 获取失败'));
					}

					let access_token = headers['access_token'];
					this.updateToken(access_token);

					// 通知其他地方可以继续执行
					Observer.emit('refresh_token_end', access_token);

					// 移除监听事件
					Observer.remove('refresh_token_end');

					this.is_request = false;

					// 返回数据
					resolve(access_token);
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