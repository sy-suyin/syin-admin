/**
 * 监听者
 */

class Observer {

	static handlers = {}

	/**
	 * 添加观察事件
	 *
	 * @param {string}		name		事件名称
	 * @param {function}	callback	回调函数
	 */
	static on(name, callback){
		if(!this.funcExist(name)){
			this.handlers[name] = [];
		}

		this.handlers[name].push(callback);
	}

	/**
	 * 触发事件
	 *
	 * @param {string}		name	事件名称
	 */
	static emit(name){
		var args = [].slice.call(arguments, 1);// 第一个参数之外的参数全部会传给回调函数

		if(this.funcExist(name)){
			this.handlers[name].forEach(func => {
				func.apply(null, args);
			});
		}
	}

	/**
	 * 判断事件是否存在
	 *
	 * @param {string}		name	事件名称
	 */
	static funcExist(name){
		if(! this.handlers.hasOwnProperty(name)){
			return false;
		}

		return true;
	}

	/**
	 * 删除观察事件
	 * 
	 * @param {string} 		name 	事件名称
	 */
	static remove(name){
		delete this.handlers[name];
	}
}

export default Observer;