/**
 * 前端请求方法, 对axios进行封装
 */

import Qs from 'qs';
import axios from 'axios'
import store from '@/vuex/store';
import {timeout, key as app_key, not_logged_allow} from '@/config/reuqest';
import {checkPermission} from '@/libs/util.js';
import Tokenn from '@/libs/Token';
import Vue from 'vue'


let tokenInstance = new Tokenn();


class Request{
	constructor (base_url = '') {
		this.base_url = base_url;
	}

	checkPermission(url){
		if(url == '' || url[0] != '/'){
			return true;
		}

		url = url.split('/');
		url.push('index');

		if(url.length < 3){
			return true;
		}

		let controller = url[1];
		let action = url[2];

		if(not_logged_allow.hasOwnProperty(controller) && 
			not_logged_allow[controller].indexOf(action) != -1
		){
			return true;	
		}

		return checkPermission(controller, action);
	}

	interceptors(instance, options){
		// 请求拦截
		instance.interceptors.request.use(config => {
			// 判断权限
			if(false == this.checkPermission(config.url)){
				return Promise.reject(new Error('很抱歉, 你暂无该数据请求权限'));
			}

			// 重定向请求链接
			config.url = this.buildUrl(config.url);

			config.headers = {
				'token': store.getters['auth/token'],
				'key': app_key
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
			const res = response.data;

			if(response.status !== 200){
				return Promise.reject(new Error('服务器未响应，请稍后重试'));
			}

			return res;
		}, error => {
			if(!!error.isAxiosError){
				const { response: { status, statusText, data: { msg = '服务器未响应，请稍后重试' } }} = error;

				if(status == 401){
					// token 过期
					return	tokenInstance.getRefreshToken().then(token => {
						let config = error.config;
						config.headers.token = token;
						return axios.request(config).then(response => {
							const res = response.data;

							if(response.status !== 200){
								return Promise.reject(new Error('服务器未响应，请稍后重试'));
							}

							return res;
						});
					}).catch(e => {
						console.log(e);
						// setTimeout(() => {
						// 	store.commit('auth/logout');
						// }, 1500);
						// return Promise.reject(new Error('账号过期, 请重新登录'));
					});
					// return tokenInstance.getrefreshToken().then((res)=>{
					// 	window.localstorage.token = res.token;
					// 	return axios.request(error.config);
					// });
					// setTimeout(() => {
					// 	store.commit('auth/logout');
					// }, 1500);
					// return Promise.reject(new Error('账号过期, 请重新登录'));
				}else if(status != 200){
					return Promise.reject(new Error('服务器未响应，请稍后重试'));
				}
			}

			return Promise.reject(error)
		})
	}

	buildUrl(url){
		if(url == '' || url[0] != '/'){
			return url;
		}

		url = this.base_url + url;
		return url;
	}

	request(options){
		const instance = axios.create({timeout})
		this.interceptors(instance, options);
		return instance(options);
	}
}

export default Request