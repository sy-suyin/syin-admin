/**
 * 新前端请求方法
 */

import Qs from 'qs';
import axios from 'axios'
import store from '@/vuex/store';
import {Message, Alert} from 'element-ui'
import {timeout, key as app_key} from '@/config/reuqest';

class Request{
	constructor (base_url = '') {
		this.base_url = base_url;
	}

	interceptors(instance, options){
		// 请求拦截
		instance.interceptors.request.use(config => {
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
			return Promise.reject(error)
		});

		// 响应拦截
		instance.interceptors.response.use(response => {
			const res = response.data;

			if(response.status !== 200){
				return Promise.reject(new Error('服务器未响应，请稍后重试'));
			}
	
			if(response.headers && typeof(response.headers.token) != 'undefined'){
				store.commit('auth/updateToken',response.headers.token);
			}

			return res;
		}, error => {
			const { response: { status, statusText, data: { msg = '服务器未响应，请稍后重试' } }} = error;

			if(status == 403){
				setTimeout(() => {
					store.commit('auth/logout');
				}, 1500);
				return Promise.reject(new Error('账号过期, 请重新登录'));

				// Alert('账号过期, 请重新登录', '过期提示', {
				// 	confirmButtonText: '确定',
				// 	callback: action => {
				// 	}
				// });
				return false;
			}else if(status != 200){
				return Promise.reject(new Error('服务器未响应，请稍后重试'));
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
		options.url = this.buildUrl(options.url);
		this.interceptors(instance, options);
		return instance(options);
	}
}

export default Request