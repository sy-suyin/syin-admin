import Config from '@/config/common';
import { Message } from 'element-ui';
import Qs from 'qs';
import axios from 'axios';
import store from '@/vuex/store';

axios.defaults.withCredentials=false;

/**
 * 生成api网站请求网址
 */
export function buildUrl(url){
	return Config.api_url + url;
}

/**
 * 发送AJAX请求
 *
 * @param url            请求URL
 * @param method         请求类型，取值post|get
 * @param params         发送数据，对象格式：如{id: 1, ...}；字符串形式：如'id=1&cid=0...'
 * @param responseType   服务器响应的数据类型，可以是 'arraybuffer', 'blob', 'document', 'json', 'text', 'stream'
 *
 * @return Promise
 */
export function request(url='', method='', params={}, responseType='json') {
	return new Promise((resolve, reject) => {
		if(typeof(url) === 'undefined' || url === ''){
			if(window.location.hash != ''){
				url = '/' + window.location.hash.substr(1, window.location.hash.length);
			}else{
				url = window.location.href;
			}
		}

		if(url != '' && url[0] == '/'){
			url = buildUrl(url);
		}

		let config = {
			url
		};

		config.method = (typeof(method) === 'undefined' || method === '') ? 'get' : method;
		params = (typeof(params) === 'undefined' || params === '') ? {} : params;
		config.responseType = (typeof(responseType) === 'undefined' || responseType === '') ? 'json' : responseType;
		config.headers = {
			'token': store.getters['auth/token'],
			'key': Config.key
		};

		if(config.method == 'post'){
			config['data'] = Qs.stringify(params);
			config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
		}else{
			config['params'] = params;
		}

		axios(config).then(function(response){
			if(response.status !== 200){
			// if(response.status !== 200 || response.statusText !== 'OK'){
				Message({
					showClose: true,
					message: '服务器未响应，请稍后重试',
					type: 'error',
					duration: 3000
				});
			}

			if(response.data && typeof(response.data.status) != 'undefined' && response.data.status < 0){
				// 这里退出登录

				store.commit('auth/logout');
				return false;
			}

			if(response.headers && typeof(response.headers.token) != 'undefined'){
				store.commit('auth/updateToken',response.headers.token);
			}

			resolve(response.data);
		}).catch(function(error){
			reject(error);
		});
	});
}

/**
 * 请求成功处理
 *
 * @param {object}  result  后端返回的数据
 * @param {bool}    tips    是否需要在请求失败时进行提示
 * @param {objectg} config  进行提示时的额外参数
 * @param {*} 		target  vue中传入this即可
 * @param {string} 	url  	请求失败提示完跳转页面, 为空不跳转
 *
 */
export function reqSuccess(result, tips = false, config){
	return new Promise((resolve, reject) => {
		let {
			target = null,
			url = '',
		} = config;

		if(result && typeof(result.status) != 'undefined' && result.status > 0){
			resolve(result.result);
		}
		else if(result && typeof(result.msg) != 'undefined' && result.msg != ''){
			tips && target.message(result.msg, 'warning', 3000, url);
			reject(new Error(result.msg));
		}
		else{
			tips && target.message('服务器未响应，请稍后重试', 'warning', 3000, url);
			reject(new Error('服务器未响应，请稍后重试'));
		}
	})
}