import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
import auth from './auth'
import access from './access'
import settings from './settings'

export default new Vuex.Store({
	namespaced: true,

	modules: {
		auth,
		access,
		settings
	}
})