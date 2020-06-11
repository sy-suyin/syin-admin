const state = {
	logs: []
}

const getters = {
	logs: state=>state.logs,
}

const mutations = {
	add(state, log){
		state.logs.push(log);
	},

	clear(state){
		state.logs.splice(0)
	}
}

const actions = {
	addErrorLog({ commit }, log){
		commit('add', log)
		state.logs.push(log);
	},

	clearErrorLog({ commit }) {
		commit('clear');
	}
}

export default {
	namespaced: true,
    state,
    getters,
	mutations,
	actions
}