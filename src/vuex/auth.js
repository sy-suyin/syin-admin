import Vue from 'vue'

const state = {
	hash:'',
	currentUser:'',
}

const getters = {
	hash:state=>state.hash,
	user:state=>state.currentUser,
}

const mutations = {

	/**
	 * 设置登录
	 */
	set_login(state, user){
		state.currentUser = user;

		localStorage.setItem('currentUser',JSON.stringify(user));
	},

	/*
	 * 退出登录
	 */
	logout(state){
		state.hash = '';
		state.currentUser = null;
		localStorage.removeItem('currentUser');
		localStorage.removeItem('authHash');
		router.push({name:'login'});
	},

	/*
	 * 更新hash
	 */
	updateHash(state,hash){
		state.hash = hash;

		localStorage.setItem('authHash',hash);
	}
}

export default {
    state,
    getters,
    mutations
}