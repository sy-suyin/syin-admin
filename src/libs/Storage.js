/**
 * 封装localStorage操作
 */

import * as Crypto from '@/libs/crypto.js';

export default class Storage {

	/**
	 * 存储数据
	 * 
	 * @param {string} key   	存储时使用的键
	 * @param {mixed}  data  	存储的数据
	 * @param {bool}   encrypt	是否需要对存储的数据进行加密
	 * 
	 */
	static set(key, data, encrypt = true){
		if(typeof data == 'object'){
			data = JSON.stringify(data);
		}

		// 对数据进行加密
		if(encrypt){
			data = Crypto.aes_encrypt(data);
		}

		localStorage.setItem(key, data);
	}

	/**
	 * 获取存储数据
	 * 
	 * @param {string} 	key   	存储时使用的键
	 * @param {bool}   	json  	存储的是否是json格式
	 * @param {bool}   	decrypt	是否需要对存储的数据进行解密
	 * 
	 */
	static get(key, {
		json = false,
		decrypt = true,
	} = {}){
		let result = '';

		if(!localStorage.hasOwnProperty(key)){
			return false;
		}

		result = localStorage.getItem(key).trim();

		if(result.length < 1){
			return;
		}

		if(decrypt){
			result = Crypto.aes_decrypt(result);

			if(!result){
				return false;
			}
		}

		if(json){
			try{ 
				result = JSON.parse(result);
			} catch(e) { 
				return false;
			}
		}

		return result;
	}

	/**
	 * 删除存储数据
	 * 
	 * 实例: remove('ky1', 'kyw', 'ky4');
	 * 
	 */
	static remove(){
		for(let i = 0, len = arguments.length; i < len; i++){
			let key = arguments[i];

			localStorage.removeItem(key);
		}
	}

	/**
	 * 移除所有存储数据
	 */
	static clear(){
		localStorage.clear();
	}
}