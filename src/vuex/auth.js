import Storage from '@/libs/Storage.js';
import Cookie from '@/libs/Cookie';
import {aes_encrypt, aes_decrypt} from '@/libs/crypto';

const state = {
	token:'',
	user:'',
	refresh_token:'',
	refresh_token_url: '',

	// 授权时间, 本地获取到新 token 的时间
	auth_time: 0,
	// 登录时间, 本地登录成功的时间
	login_time: 0,
}

const getters = {
	user: state=>state.user,
	token: state=>state.token,
	refresh_token: state=>state.refresh_token,
	refresh_token_url: state=>state.refresh_token_url,
	auth_time: state=>state.auth_time,
	login_time: state=>state.login_time,
}

const mutations = {

	/**
	 * 设置登录
	 */
	setLogin(state, data){
		// 设置登录时间
		let time = +(new Date());
		state.login_time = time;
		Storage.set('login_time', time, false);

		this.commit('auth/updateUser', data.user);
		this.commit('auth/updateToken', data.token);
		this.commit('auth/updateRefreshToken', data);
	},

	/*
	 * 退出登录
	 */
	logout(state){
		state.hash = '';
		state.user = null;

		// 清除所有存储, 以免与新登录账号部分数据重叠融合
		Storage.clear();

		// 直接刷新: 重置动态添加的路由
		location.reload()
	},

	/**
	 * 更新用户信息
	 */
	updateUser(state, user){
		state.user = user;
		Storage.set('login_user', user);
	},

	/*
	 * 更新hash
	 */
	updateToken(state, token){
		let time = +(new Date());
		state.token = token;
		state.auth_time = time;
		Storage.set('auth_time', time, false);

		token = aes_encrypt(token);
		Cookie.write('auth_token', token, null, null, null, false);
	},

	/**
	 * 更新refresh_token
	 */
	updateRefreshToken(state, {refresh_token, refresh_token_url}){
		state.refresh_token = refresh_token;
		refresh_token = aes_encrypt(refresh_token);
		Cookie.write('auth_refresh_token', refresh_token, null, null, null, false);
		Storage.set('refresh_token_url', refresh_token_url, true);
	},

	/**
	 * 重新从本地读取token
	 */
	reloadToken(state){
		let token = Cookie.read('auth_token');
		let refresh_token = Cookie.read('auth_refresh_token');
		let auth_time = Storage.get('auth_time', {json: false, decrypt: false});
		let refresh_token_url = Storage.get('refresh_token_url', refresh_token_url, {json: false});

		token = token.trim();
		refresh_token = refresh_token.trim();

		// 如果token一致则不修改
		if(token == state.token && refresh_token == state.refresh_token){
			return;
		}

		state.token = '';
		state.refresh_token = '';
		state.refresh_token_url = '';
		state.auth_time = auth_time;

		if(token == '' || refresh_token == '' || refresh_token_url == ''){
			return;
		}

		token = aes_decrypt(token);
		refresh_token = aes_decrypt(refresh_token);

		if(!token || !refresh_token){
			return;
		}

		state.token = token;
		state.refresh_token = refresh_token;
		state.refresh_token_url = refresh_token_url;
	},

	/*
	 * 重新加载, 从缓存中读取数据
	 */
	reload(state){
		this.commit('auth/reloadToken');

		if(state.token == ''){
			return;
		}

		let user = Storage.get('login_user', {json: true});
		let login_time = Storage.get('login_time', {json: false, decrypt: false});

		if(!user){
			state.token = '';
			state.refresh_token = '';
			return;
		}

		state.login_time = login_time;
		this.commit('auth/updateUser', user);
	},

	/**
	 * 锁屏
	 */
	lock(state, pwd){
		let user = state.user;
		user.is_lock = true;
		user.lock_pwd = pwd;
		this.commit('auth/updateUser', user);
	},

	/**
	 * 锁屏解除
	 */
	unlock(state){
		let user = state.user;
		user.is_lock = false;
		user.lock_pwd = '';
		this.commit('auth/updateUser', user);
	}
}

export default {
	namespaced: true,
    state,
    getters,
    mutations
}