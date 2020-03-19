import * as util from '@/libs/util.js';
class Table{

	target = null;
	ids = [];

	constructor(target){
		this.target = target;
	}

	/** 
	 * 表单常用提交操作封装
	 * 
	 * @param int id			操作项目id, 当为-1时表示为批量操作
	 * @param int operate		操作类型
	 * @param string url	 	提交链接
	 */
	post(id, operate, url){
		var args = {
			operate
		};

		if(id == -1){
			args.id = this.ids;

			if(args.id.length < 1){
				this.target.message('请选择需要操作的项目');
				return Promise.reject('请选择需要操作的项目');
			}
		}else{
			args.id = id;

			if(! +args.id){
				this.target.message('请选择需要操作的项目');
				return Promise.reject('请选择需要操作的项目');
			}
		}

		return util.post(url, args).then((res)=>{
			return this.success(res);
		}).catch((e)=>{
			let msg = e.message || e;
			this.error(msg);
			return Promise.reject(msg);
		});
	}

	/**
	 * 对消息进行提示
	 * @param string msg 	提示次数组, 以 | 分隔
	 */
	tip(msg){
		return this.target.$confirm(msg, '操作提示', {
			confirmButtonText: '确定',
			cancelButtonText: '取消',
			type: 'warning'
		});
	}

	/**
	 *请求成功时回调方法
	 */
	success(res){
		if(res && typeof(res.status) != 'undefined' && res.status > 0){
			return Promise.resolve(res);
		}
		else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
			return Promise.reject(res.msg);
		}
		else{
			return Promise.reject('服务器未响应，请稍后重试');
		}
	}

	/**
	 * 请求失败时回调方法
	 */
	error(msg){
		this.target.message(msg, 'warning');
	}

	/**
	 * 异步请求 删除/恢复 数据方法
	 *
	 * @param int id			操作项目id, 当为-1时表示为批量操作
	 * @param int operate		操作类型
	 * @param string url	 	提交链接
	 */
	delete(id, operate, url){
		let msg_words = ['还原', '删除'];
		let msg = '您确定要执行'+(msg_words[operate] || msg_words[0])+'操作吗?';

		return this.tip(msg).then(()=>{
			this.post(id, operate, url).then((res)=>{
				history.go(0);
			}).catch(e => {
			});
		});
	}

	/**
	 * 异步请求 删除/恢复 数据方法
	 *
	 * @param int id			操作项目id, 当为-1时表示为批量操作
	 * @param int operate		操作类型
	 * @param string url	 	提交链接
	 */
	disabled(id, operate, url){
		let msg_words = ['启用', '禁用'];
		let msg = '您确定要执行'+(msg_words[operate] || msg_words[0])+'操作吗?';

		return this.tip(msg).then(()=>{
			this.post(id, operate, url).then((res)=>{
				history.go(0);
			}).catch(e => {
			});
		});
	}

	setIds(ids){
		this.ids = ids;
	}

	// 代理方法
	proxy(target, handler){
		const proxy = new Proxy(target, handler);
		return proxy;
	}
}

export default Table;