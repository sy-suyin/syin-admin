import Storage from '@/libs/Storage.js';
import Cookie from '@/libs/Cookie';
import {aes_encrypt, aes_decrypt} from '@/libs/crypto';
import userApi from '@/api/user';

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
	 * 设置登录信息
	 */
	setLogin(state, result){
		// 设置登录时间
		let time = +(new Date());

		this.commit('config/set', {
			key: 'domain',
			value: result.config.domain,
		});

		this.commit('style/set', {
			key: 'sidebar_background_imgs',
			value: result.config.sidebar_imgs
		});

		// 存储相关登录信息
		state.login_time = time;
		result.user.avatar = result.config.domain + result.user.avatar;
		this.commit('auth/updateUser', result.user);
		Storage.set('login_time', time, false);

		// 设置权限数据
		this.commit('access/set', result.blocklist);
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
		Cookie.write('auth_token', token, 86400 * 30, null, null, false);
	},

	/**
	 * 更新refresh_token
	 */
	updateRefreshToken(state, {refresh_token, refresh_token_url}){
		state.refresh_token = refresh_token;
		state.refresh_token_url = refresh_token_url;

		refresh_token = aes_encrypt(refresh_token);
		Cookie.write('auth_refresh_token', refresh_token, 86400 * 30, null, null, false);
		Storage.set('refresh_token_url', refresh_token_url, true);
	},

	/**
	 * 重新从本地读取token
	 */
	reloadToken(state){
		let token = Cookie.read('auth_token');
		let refresh_token = Cookie.read('auth_refresh_token');
		let auth_time = Storage.get('auth_time', {json: false, decrypt: false});
		let refresh_token_url = Storage.get('refresh_token_url', {json: false});

		// 如果token一致则不修改
		if(token == state.token && refresh_token == state.refresh_token){
			return;
		}

		state.token = '';
		state.refresh_token = '';
		state.refresh_token_url = '';
		state.auth_time = 0;

		if(!token || !refresh_token || !refresh_token_url){
			return;
		}

		token = aes_decrypt(token);
		refresh_token = aes_decrypt(refresh_token);

		if(!token || !refresh_token){
			this.commit('auth/clearToken');
			return;
		}

		state.token = token;
		state.auth_time = auth_time;
		state.refresh_token = refresh_token;
		state.refresh_token_url = refresh_token_url;

	},

	/**
	 * 清除token
	 */
	clearToken(state){
		state.token = '';
		state.refresh_token = '';
		Cookie.remove('auth_token');
		Cookie.remove('auth_refresh_token');
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
	},
}

const actions = {

	/*
	 * 重新加载, 从缓存中读取数据
	 */
	reload({state, rootGetters}){
		this.commit('auth/reloadToken');

		if(!state.token){
			return;
		}

		let user = Storage.get('login_user', {json: true});
		let login_time = Storage.get('login_time', {json: false, decrypt: false});

		if(user){
			state.login_time = login_time;
			this.commit('auth/updateUser', user);
		}else{
			// 使用 token 向后端请求数据, 获取用户信息以及配置信息
			userApi.tokenLogin().then(result => {
				this.commit('auth/setLogin', result);
				location.reload();
			}).catch(e => {
				this.commit('auth/clearToken');
			});
		}
	},
}

export default {
	namespaced: true,
    state,
    getters,
	mutations,
	actions
}