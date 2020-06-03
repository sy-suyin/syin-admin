import axios from 'axios'
import {baseUrl} from '@/config/reuqest';
import store from '@/vuex/store'
import Observer from '@/libs/Observer.js';
import { setTimeout } from 'core-js';

const BASE_URL = process.env.NODE_ENV === 'development' ? baseUrl.dev : baseUrl.pro;

class Tokenn {

	is_request = false;

	/**
	 * 刷新token
	 */
	getRefreshToken(){
		return new Promise((resolve, reject) => {
			console.log(this.is_request);
			
			if(this.is_request){
				Observer.on('refresh_token_end', (token)=>{
					resolve(token);
				});
			}else{
				this.is_request = true;
				let url = BASE_URL+'/index/refreshtoken';
				axios.post(url).then(res => {
					res = res.data;

					// 此处判断后端传过来的数据有没有问题

					// 保存提交的数据
					let token = res.result.token;

					store.commit('auth/updateToken', token);
					Observer.emit('refresh_token_end', token);
					this.is_request = false;
					resolve(token);
				})
			}
		});
	}
}

export default Tokenn;