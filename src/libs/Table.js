import { getType } from '@/libs/util.js';
import { post } from '@/libs/api.js';

class Table{
	target = null;
	ids = [];

	constructor(target){
		this.target = target;
	}

	/**
	 * 执行相应表单操作并使用POST提交数据
	 * @param {string} url 	提交链接
	 * @param {mixed}  data 提交的数据, 当为-1时使用表格选中栏的id数组
	 * @param {mixed}  mark 操作标示, 可传入两种数据, 字符串或对象. 为字符串时会转换为对象, 转化后键为 mark
	 */
	execute(url, data = -1, mark = ''){
		let args = {};

		// 当不传数据时, 直接使用表格所有选中项的id
		if(data === -1){
			if(this.ids.length < 1){
				this.target.message('请选择需要操作的项目');
				return Promise.reject('请选择需要操作的项目');
			}

			args.id = this.ids;
		}else{
			// 当为数字时, 即可判断是单项删除之类的操作
			if('number' == getType(data)){
				if(+data < 1){
					this.target.message('请选择需要操作的项目');
					return Promise.reject('请选择需要操作的项目');
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

		this.target.loading(true);
		return post(url, args, true).then((res)=>{
			this.success(res, args);
		}).catch((e)=>{
			this.error(e);
		});
	}

	/**
	 * 对消息进行提示
	 * @param {string} msg 		提示消息
	 * @param {object} config
	 *
	 */
	tip(msg, config){
		return this.target.$confirm(msg, '操作提示', {
			confirmButtonText: '确定',
			cancelButtonText: '取消',
			type: 'warning'
		}).then(res => {
			let {
				url = '',
				data = -1,
				mark = '',
			} = config;

			return this.execute(url, data, mark).then(res=>{
				return Promise.resolve(res);
			}).catch(e => {
				// 此处抛出错误, 下面的错误捕捉如不继续抛出错误, 将会导致错误信息传入调用该方法后的then方法中
				return Promise.reject(e);
			});
		}).catch(e => {
			return Promise.reject(e);
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

	setSelected(ids){
		this.ids = ids;
	}
}

export default Table;