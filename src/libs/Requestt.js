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
	
			if(response.data && typeof(response.data.status) != 'undefined' && response.data.status < 0){
				// 这里退出登录

				Aalert('账号过期, 请重新登录', '解锁密码', {
					confirmButtonText: '确定',
					callback: action => {
						store.commit('auth/logout');
					}
				});

				return Promise.reject(new Error(res.msg || 'Error'));
			}

			if(response.headers && typeof(response.headers.token) != 'undefined'){
				store.commit('auth/updateToken',response.headers.token);
			}

			return res;
		}, error => {
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