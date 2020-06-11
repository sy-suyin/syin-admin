import Vue from 'vue'
import App from './App.vue'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './store'
import Observer from '@/libs/Observer'
import './icons' // icon
import './libs/error-log'

Vue.config.productionTip = false
Vue.prototype.$event = Observer;
Vue.use(ElementUI);

// 重载用户数据
store.commit('auth/reload');

// 重载路由数据
store.commit('access/reload');

// 读取本地缓存的用户配置
store.commit('style/init');
store.commit('config/init');

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
