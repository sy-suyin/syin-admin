import Storage from '@/libs/Storage.js';

const state = {
	token:'',
	currentUser:'',
}

const getters = {
	token:state=>state.token,
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
	updateToken(state,token){
		state.token = token;

		Storage.set('authToken', token)
	},

	/*
	 *重新加载, 从缓存中读取数据
	 */
	reload(){
		let token = Storage.get('authToken');
		let user = Storage.get('currentUser', {json: true});

		if(token && user){
			user && this.commit('auth/setLogin', user);
			this.commit('auth/updateToken', token);
		}
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