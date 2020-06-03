import axios from 'axios'
import {baseUrl} from '@/config/reuqest';
import store from '@/vuex/store'
import Observer from '@/libs/Observer.js';

const BASE_URL = process.env.NODE_ENV === 'development' ? baseUrl.dev : baseUrl.pro;

class Token {

	static is_request = false;

	/**
	 * 获取本地存储的token
	 */
	static getToken(){
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
				let url = BASE_URL+'/index/refreshtoken';
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