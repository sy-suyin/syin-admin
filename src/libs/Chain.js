/**
 * Promise 链式封装, 使代码更加优雅
 *
 */

class Chain{

	// 事件键, 执行顺序从0开始
	keys = [];

	// 事件列表
	binds = {};

	/**
	 * 设置key的执行顺序
	 * 
	 * @param {*} keys 
	 */
	sort(keys){
		this.keys = keys;
	}

	/**
	 * 
	 * @param {*} key 
	 * @param {*} resolve 
	 * @param {*} reject 
	 */
	bind(key, resolve = null, reject = null){
		if(!resolve && !reject){
			return false;
		}

		let key_resolve = `${key}_resolve`;
		let key_reject = `${key}_reject`;

		this.binds[key_resolve] = resolve;
		this.binds[key_reject] = reject;
	}

	/**
	 * 触发
	 */
	commit(param = null){
		let chain = [];

		if(this.keys.length < 1){
			return Promise.reject(new Error('未绑定任何键'));
		}

		// 排列执行顺序
		this.keys.forEach(key => {
			let key_resolve = `${key}_resolve`;

			if(! this.binds.hasOwnProperty(key_resolve)){
				return;
			}

			let key_reject = `${key}_reject`;
			chain.push(this.binds[key_resolve], this.binds[key_reject]);
		});

		if(chain.length < 1){
			return Promise.reject(new Error('未绑定任何方法'));
		}

		let promise = Promise.resolve();

		while (chain.length) {
			promise = promise.then(chain.shift(), chain.shift());
		}

		return promise;
	}
}

export default Chain;