/**
 * Promise 链式封装, 使代码更加优雅
 *
 */

class Chain{

	// 事件列表
	chains = {};

	/**
	 * 添加
	 * 
	 * @param {*} key 
	 * @param {*} resolve 
	 * @param {*} reject 
	 */
	add(key, resolve = null, reject = null){
		if(!resolve && !reject){
			return false;
		}

		this.chains[key] = {
			resolve,
			reject
		};

		console.log(this.chains);
	}

	/**
	 * 触发
	 */
	commit(params = {}){
		console.log('commit');
		console.log(this.chains);

		let chain_keys = Object.keys(this.chains);
		console.log('commit');

		if(chain_keys.length < 1){
			return Promise.reject(new Error('未绑定任何方法'));
		}

		console.log('commit');
		let promise = Promise.resolve(params);

		for(let key in this.chains){
			let chain = this.chains[key];

			promise = promise.then(chain.resolve, chain.reject);
		}

		console.log(promise);

		return promise;
	}

	/**
	 * 清除
	 */
	clear(){
		this.chains = {};
	}
}

export default Chain;