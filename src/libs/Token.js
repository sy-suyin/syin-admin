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
	 * 保存token
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
			if(this.is_request){
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
						|| typeof(res.token) == 'undefined' 
						|| res.status != 1
						|| res.token == ''
					){
						reject(new Error('token获取失败'));
					}

					// 保存提交的数据
					let token = res.result.token;

					// 更新本地存储的token
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

	/**
	 * 执行请求, 当token已过期, 其他的请求正在获取新的token时, 将会挂起该请求直到新token获取完成
	 * 
	 * @param {*} fn 
	 */
	static next(fn){
		return new Promise((resolve, reject) => {
			if(!this.is_request){
				resolve(fn());
			}else{
				Observer.on('refresh_token_end', (token)=>{
					resolve(fn());
				});
			}
		});
	}
}

export default Token;