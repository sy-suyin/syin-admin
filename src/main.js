import Vue from 'vue'
import App from './App.vue'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './vuex/store'

Vue.config.productionTip = false
Vue.use(ElementUI);

let token = localStorage.getItem('authToken');
let user = localStorage.getItem('currentUser');
if(token && user){
	user = JSON.parse(user);
	user && store.commit('set_login',user);

	store.commit('updateToken',token);
}

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
