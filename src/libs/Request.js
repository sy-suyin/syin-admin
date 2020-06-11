/**
 * 前端请求方法, 对axios进行封装
 */

import Qs from 'qs';
import axios from 'axios'
import store from '@/store';;
import { checkPermission, config } from '@/libs/util';
import Token from '@/libs/Token';
import Observer from '@/libs/Observer.js';

class Request{
	constructor (base_url = '') {
		this.base_url = base_url;
	}

	/**
	 * 对请求的链接进行权限判断
	 *
	 * @param {string} url 请求链接
	 */
	checkPermission(url){
		if(url == '' || url[0] != '/'){
			return true;
		}

		url = url.split('/');
		url.push('index');

		if(url.length < 3){
			return true;
		}

		let not_logged_allow = config('not_logged_allow');
		let controller = url[1];
		let action = url[2];

		if(not_logged_allow.hasOwnProperty(controller) &&
			not_logged_allow[controller].indexOf(action) != -1
		){
			return true;
		}

		return checkPermission(controller, action);
	}

	/**
	 * 设置拦截器
	 *
	 * @param {*} instance
	 * @param {*} options
	 */
	interceptors(instance){
		// 请求拦截
		instance.interceptors.request.use(config => {
			// 判断权限
			if(false == this.checkPermission(config.url)){
				return Promise.reject(new Error('很抱歉, 你暂无该数据请求权限'));
			}

			// 重定向请求链接
			config.url = this.buildUrl(config.url);

			config.headers = {
				'Authorization': Token.getToken(),
			};

			// 对post提交的数据进行额外处理
			if(config.method == 'post' && config.hasOwnProperty('data')){
				config.data = Qs.stringify(config.data);
				config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
			}

			return config;
		}, error => {
			return Promise.reject(error);
		});

		// 响应拦截
		instance.interceptors.response.use(response => {
			return this.responseSuccess(response);
		}, error => {
			if(error.hasOwnProperty('isAxiosError') && error.isAxiosError){
				const { response: { status, statusText, data: { msg = '服务器未响应，请稍后重试' } }} = error;

				if(status == 401){
					// token 过期, 获取新 token
					return	Token.refreshToken().then(token => {
						let config = error.config;
						config.headers['Authorization'] = 'Bearer ' + token;

						return axios.request(config).then(response => {
							return this.responseSuccess(response);
						});
					}).catch(e => {
						setTimeout(() => {
							store.commit('auth/logout');
						}, 15000);

						return Promise.reject(new Error('账号过期, 请重新登录'));
					});
				}else if(status != 200){
					return Promise.reject(new Error('服务器未响应，请稍后重试'));
				}
			}

			return Promise.reject(error);
		});
	}

	/**
	 * 处理请求成功时返回数据
	 *
	 * @param {*} response
	 */
	responseSuccess(response){
		const result = response.data;
		const headers = response.headers;

		if(response.status !== 200){
			return Promise.reject(new Error('服务器未响应，请稍后重试'));
		}

		// 获取并保存 token
		if(headers.hasOwnProperty('token_type') && headers.hasOwnProperty('access_token')){
			if(headers['token_type'] == 'bearer'){
				let access_token = headers['access_token'];

				store.commit('auth/updateToken', access_token);

				if(headers.hasOwnProperty('refresh_token') && headers.hasOwnProperty('refresh_token_url')){
					let refresh_token = headers['refresh_token'];
					let refresh_token_url = headers['refresh_token_url'];

					store.commit('auth/updateRefreshToken', {
						refresh_token,
						refresh_token_url,
					});
				}
			}
		}

		return result;
	}

	/**
	 * 构建完整请求链接
	 *
	 * @param {string} url
	 */
	buildUrl(url){
		if(url == '' || url[0] != '/'){
			return url;
		}

		url = this.base_url + url;
		return url;
	}

	/**
	 * 将请求放入请求队列中
	 * 如果请求被挂起, 所有在队列中的请求都将在请求挂起取消后继续
	 *
	 * @param {*} fn
	 */
	queue(fn){
		if(Token.isRequest()){
			return new Promise((resolve, reject) => {
				Observer.on('refresh_token_end', (token)=>{
					resolve(fn());
				});
			});
		}else{
			return fn()
		}
	}

	/**
	 * 请求数据
	 *
	 * @param {object} options
	 */
	request(options){
		let timeout = config('timeout', 0);
		const instance = axios.create({timeout});
		this.interceptors(instance);
		return this.queue(()=>{return instance(options)});
	}
}

export default Request