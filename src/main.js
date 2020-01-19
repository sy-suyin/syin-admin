import Vue from 'vue'
import App from './App.vue'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './vuex/store'

Vue.config.productionTip = false
Vue.use(ElementUI);

// 重载路由数据
store.commit('access/reload');

// 重载用户数据
store.commit('auth/reload');

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
