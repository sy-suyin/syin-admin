/**
 * 封装分页跳转加载数据相关操作, 需先引入common
 * 注: 单独封装, 主要是考虑后续实际项目可能会需要在详情页进行分页展示数据
 */

import * as Util from '@/libs/util.js';

export const detail = {
	data(){
		return {
			id: 0,
		}
	},
	methods: {

		/**
		 * 获取详情数据
		 * 
		 * @param {int} 	 id  	   数据id
		 * @param {string}	 url 	   数据请求地址
		 * @param {string}   error_url 数据请求失败时跳转地址
		 * 
		 */
		detail(id, url, error_url=''){
			id = +id || 0;

			if(id < 1){
				this.message('未找到相关数据, 请检查后重试', 'warning', 3000, error_url);
				return Promise.reject('请选择需要操作的项目');

			}

			url += `/id/${id}`;

			this.loading(true);
			return Util.get(url).then(res => {
				this.loading(false);
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.id = id;
					return Promise.resolve(res.result);
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg, 'warning', 3000, error_url);
					return Promise.reject(res.msg);
				}
				else{
					let msg = '服务器未响应，请稍后重试';
					this.message('服务器未响应，请稍后重试', 'warning', 3000, error_url);
					return Promise.reject(msg);
				}
			}).catch(err => {
				this.loading(false);
				let msg = (err instanceof Error) ? '网络异常, 请稍后重试' : err;
				this.$message(msg, 'warning', 3000, error_url);
				return Promise.reject(e);
			});
		}
	},
}