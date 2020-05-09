import Request from '@/libs/Request';
import {baseUrl} from '@/config/reuqest';

const BASE_URL = process.env.NODE_ENV === 'development' ? baseUrl.dev : baseUrl.pro;

const service = new Request(BASE_URL);

/**
 * 发送AJAX请求
 *
 * @param {string} 	url         请求URL
 * @param {string} 	method      请求类型，取值post|get
 * @param {*} 		data      	POST提交数据
 * @param {bool} 	dispose		是否在后端成功返回数据时, 对数据进行整理, 并只返回处理后的无状态数据
 * 自动化
 * @return Promise
 */
export function request(options) {
	let {dispose = false} = options;

	let response = service.request(options);

	if(dispose){
		return response.then((res)=>{
			if(res && typeof(res.status) != 'undefined' && res.status > 0){
				return res.result;
			}
			else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
				return Promise.reject(new Error(res.msg));
			}
			else{
				return Promise.reject(new Error('服务器未响应，请稍后重试'));
			}
		});
	}else{
		return response;
	}
}

/**
 * get方式获取数据
 * 
 * @param {string} 	url	 	 请求URL
 * @param {bool} 	dispose	 是否在后端成功返回数据时, 对数据进行整理, 并只返回处理后的无状态数据
 */
export function get(url, dispose=false){
	return request({
		url,
		dispose,
		method: 'get', 
	});
}

/**
 * post提交数据
 * 
 * @param {string} 	url		请求URL
 * @param {object} 	data	提交给后台的数据
 * @param {bool} 	dispose	是否在后端成功返回数据时, 对数据进行整理, 并只返回处理后的无状态数据
 */
export function post(url, data={}, dispose=false){
	return request({
		url,
		data,
		dispose,
		method: 'post', 
	});
}

export default service;