
/**
 * 表格操作核心类
 */

import { MessageBox } from 'element-ui';
import { getType, isEmpty } from '@/libs/util';
import { post } from '@/libs/api.js';
import Chain from '@/libs/Chain';

class Table{

	/**
	 * 执行相应表单操作并使用POST提交数据
	 *
	 * @param {string} url 	提交链接
	 * @param {*} 	   id 	id
	 * @param {mixed}  data 提交的数据, 不为空时将仅传该数据并无视id与mark
	 * @param {mixed}  mark 操作标示, 可传入两种数据, 字符串或对象. 为字符串时会转换为对象, 转化后键为 mark
	 */
	static execute(params){
		let instance = new Chain;

		// 整理数据
		instance.add('requestCheck', this.requestCheck);

		// 弹窗确认
		if(params.hasOwnProperty('is_confirm') && params.is_confirm){
			instance.add('requestConfirm', this.requestConfirm);
		}

		// 提交数据
		instance.add('requestPost', this.requestPost);

		// 返回数据处理
		return instance.commit(params);
	}

	/**
	 * 操作前检查整合数据 
	 */
	static requestCheck(params){
		let args = {};

		if( !params.hasOwnProperty('url') || !params.url ){
			return Promise.reject(new Error('未找到请求链接'));
		}

		if(params.hasOwnProperty('data')){
			args.data = params.data;

			if(isEmpty(args.data)){
				return Promise.reject(new Error('请先选择需要操作的项目'));
			}
		} else if(params.hasOwnProperty('id')){
			args.id = params.id;

			if( getType(args.id) == 'array'){
				if(args.id.length < 1){
					return Promise.reject(new Error('请先选择需要操作的项目'));
				}
			}else if(! +args.id){
				return Promise.reject(new Error('请先选择需要操作的项目'));
			}

			// 整理操作标示数据
			if(params.hasOwnProperty('mark') && params.mark){
				if('object' != getType(params.mark)){
					args.mark = mark;
				}else{
					let mark_keys = Object.keys(params.mark);
					if(mark_keys.length > 0){
						args[mark_keys[0]] = Object.values(params.mark)[0];
					}
				}
			}
		} else {
			return Promise.reject(new Error('请先传入完整数据'));
		}
		
		params.args = args;
		return params;
	}

	/**
	 * 操作确认
	 */
	static requestConfirm(params){
		if( !params.hasOwnProperty('confirm_msg')){
			return Promise.reject(new Error('系统配置有误'));
		}

		return MessageBox.confirm(params.confirm_msg, '操作提示', {
			confirmButtonText: '确定',
			cancelButtonText: '取消',
			type: 'warning'
		}).then(res => {
			return params;
		}).catch(e => {
			return Promise.reject('');
		});
	}

	/**
	 * 提交数据
	 */
	static requestPost({ url, args }){
		return post(url, args, true).then((res) => {
			return res;
		});
	}
}

export default Table;