import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'
import access from './access'
import style from './style'
import config from './config'

Vue.use(Vuex)

export default new Vuex.Store({
	namespaced: true,

	modules: {
		auth,
		access,
		config,
		style
	}
})