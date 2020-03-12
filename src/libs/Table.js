class Table{

	target = null;
	ids = [];

	constructor(target){
		console.log(target);
		this.target = target;
	}

	/** 
	 * 表单常用提交操作封装
	 * 
	 * @param string operate	操作标示
	 * @param string url	 	提交链接
	 * @param object btn 	触发点击的按钮对象
	 * @param bool bulk		 	是否对整个表格进行处理
	 * @param bool tip		 	是否在提交前提醒用户
	 * @param string tip_words 	提示次数组, 以 | 分隔
	 */
	post(operate, url, btn, bulk, tip, tip_words){
		var target = $(target);
		var args = {
			[operate]: +target.attr('data-'+operate)
		};

		if(bulk){
			args.id = this.ids;

			if(args.ids.length < 1){
				return this.target.message('请选择需要操作的项目');
			}
		}else{
			args.id = +$(target).data('id');

			if(!args.id){
				return this.target.message('请选择需要操作的项目');
			}
		}

		if(!!tip){
			var that = this;

			if (typeof(tip_words) === 'undefined' || tip_words === ''){
				return false;
			}

			tip_words = tip_words.split('|');

			var msg = '您确定要执行';
			msg += tip_words[args[operate] * 1] || tip_words[0];
			msg += '操作吗？';
	
			layer.confirm(msg, {
				title: '系统提示',
				text: msg,
				btn: ['确定', '取消'],
			}, function(index){
				util.post(url, args).then(function(res){
					that.success(res);
				}).catch(function(){
					that.error();
				});
			}, function(index){
				layer.closeAll();
			});
		}else{
			return util.post(url, args).then(function(res){
				return this.success(res);
			}).catch(function(){
				return this.error('服务器未响应，请稍后重试');
			});
		}
	}

	/**
	 *请求成功时回调方法
	 */
	success(res){
		return Promise((resolve, reject) => {
			if(res && typeof(res.status) != 'undefined' && res.status > 0){
				resolve();
			}
			else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
				this.target.message(res.msg, 'danger');
				reject(res.msg);
			}
			else{
				this.target.message('服务器未响应，请稍后重试', 'warning');
				reject();
			}
		});
	}

	/**
	 *请求失败时回调方法
	 */
	error(msg){
		return Promise((resolve, reject) => {
			this.target.message(msg, 'warning');
			reject();
		});
	}

	delete(url, target, bulk){
		this.post('deleted', url, target, bulk, true, '恢复|删除');
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