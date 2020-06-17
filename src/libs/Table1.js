/**
 * 表格操作核心类
 */

import { MessageBox } from 'element-ui';
import { getType } from '@/libs/util';
import { post } from '@/libs/api.js';

class Table1{

	urls = {};

	constructor(urls){
		this.urls = urls;
	}

	execute(params){
		let chains = [this.buildUrl];

		// 整合参数, 可重写
		if(params.hasOwnProperty('optimget') && params.optimget){
			promise.then(params.optimget, null);
		}else{
			promise.then(this.optimget, null);
		}

		// 提示用户, 可重写
		if(params.is_confirm){
			if(params.hasOwnProperty('confirm') && params.confirm){
				promise.then(params.confirm, null);
			}else{
				promise.then(this.confirm, null);
			}
		}

		let promise = Promise.resolve(params);

		chains.forEach(fn => {
			promise = promise.then(this[fn], null);
		});

		// 异常处理
		promise = promise.then(null, this.errorHandle);

		// 生成请求链接
		this.buildUrl(params);

		// # 提示方法可重写

		// 整合参数
		// # 整合参数方法可重写

		// 执行请求

		// 处理返回, 异常由调用者负责处理
	}

	/**
	 * 获取提交的参数
	 */
	optimget(params){

		// 设置默认参数
		let {
			url = '',
			data = -1,
			mark = ''
		} = params;

		// 当不传数据时, 直接使用表格所有选中项的id
		if(data === -1){
			if(this.ids.length < 1){
				return Promise.reject(new Error('请选择需要操作的项目'));
			}

			args.id = this.ids;
		}else{
			// 当为数字时, 即可判断是单项删除之类的操作
			if('number' == getType(data)){
				if(+data < 1){
					return Promise.reject(new Error('请选择需要操作的项目'));
				}

				args.id = data;
			}else{
				args.data = data;
			}
		}

		// 整理操作标示数据
		if('object' != getType(mark)){
			if(!!mark){
				args.mark = mark;
			}
		}else{
			let mark_keys = Object.keys(mark);
			if(mark_keys.length > 0){
				args[mark_keys[0]] = Object.values(mark)[0];
			}
		}

		return params;
	}

	/**
	 * 提交前需要确认
	 */
	confirm(params){
		// 提示用户
		return MessageBox.confirm(msg, '操作提示', {
			confirmButtonText: '确定',
			cancelButtonText: '取消',
			type: 'warning'
		}).then(res => {
			return params;
		}).catch(e => {
			return Promise.reject(e);
		});
	}

	/**
	 * 提交数据
	 */
	post({
		url  = '',
		args = -1,
		mark = ''
	} = {}){
		return post(url, args, true).then((res)=>{
			return this.success(res, args);
		}).catch((e)=>{
			return this.error(e);
		});
	}
	
	/**
	 * 请求成功时回调方法
	 */
	success(res, args){
		this.target.loading(false);
		res.resquset = args;
		return Promise.resolve(res);
	}

	/**
	 * 请求失败时回调方法
	 */
	error(e){
		this.target.loading(false);
		let msg = e.message || e;
		this.target.message(msg, 'warning');
		return Promise.reject(e);
	}

	/**
	 * 获取跳转链接
	 */
	buildUrl(params){
		let url = '';
		if(this.urls.hasOwnProperty(params.page)){
			url = this.urls[params.page];
		}

		return url;
	}

}

export default Table1;