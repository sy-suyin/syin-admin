import Storage from '@/libs/Storage.js';
import Cookie from '@/libs/Cookie';
import {aes_encrypt, aes_decrypt} from '@/libs/crypto';

const state = {
	token:'',
	refresh_token:'',
	currentUser:'',
}

const getters = {
	token:state=>state.token,
	refresh_token:state=>state.refresh_token,
	user:state=>state.currentUser,
}

const mutations = {

	/**
	 * 设置登录
	 */
	setLogin(state, user){
		state.currentUser = user;

		Storage.set('currentUser', user)
	},

	/*
	 * 退出登录
	 */
	logout(state){
		state.hash = '';
		state.currentUser = null;

		// 清除所有存储, 以免与新登录账号部分数据重叠融合
		Storage.clear();

		// 直接刷新: 重置动态添加的路由
		history.go(0);
	},

	/*
	 * 更新hash
	 */
	updateToken(state, token){
		state.token = token;

		token = aes_encrypt(token);
		Cookie.write('auth_token', token, null, null, null, true);
	},

	updateRefreshToken(state,refresh_token){
		state.refresh_token = refresh_token;

		refresh_token = aes_encrypt(refresh_token);
		Cookie.write('auth_refresh_token', refresh_token, null, null, null, true);
	},

	/*
	 *重新加载, 从缓存中读取数据
	 */
	reload(){
		let token = Cookie.read('auth_token');
		let refresh_token = Cookie.read('auth_refresh_token');

		let user = Storage.get('currentUser', {json: true});

		if(!token || user || refresh_token){
			return;
		}

		token = aes_decrypt(token);
		refresh_token = aes_decrypt(refresh_token);

		if(!token || !refresh_token){
			return;
		}

		state.token = token;
		state.refresh_token = refresh_token;
		user && this.commit('auth/setLogin', user);
	},

	/**
	 * 锁屏
	 */
	lock(state, pwd){
		let user = state.currentUser;
		user.is_lock = true;
		user.lock_pwd = pwd;
		this.commit('auth/setLogin', user);
	},

	/**
	 * 锁屏接触 
	 */
	unlock(state){
		let user = state.currentUser;
		user.is_lock = false;
		user.lock_pwd = '';
		this.commit('auth/setLogin', user);
	}
}

export default {
	namespaced: true,
    state,
    getters,
    mutations
}