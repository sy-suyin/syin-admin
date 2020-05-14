import Vue from 'vue'
import App from './App.vue'
import router from './router'
// import ElementUI from 'element-ui'
// import 'element-ui/lib/theme-chalk/index.css'
import store from './vuex/store'
import Observer from '@/libs/Observer.js';

// 此优化策略参考链接, 具体待调整
// https://vincef0ng.cn/post/vue-project-optimization/#%E7%BB%93%E6%9E%9C

Vue.config.productionTip = false
Vue.prototype.$event = Observer;
// Vue.use(ElementUI);

// 重载用户数据
store.commit('auth/reload');

// 重载路由数据
store.commit('access/reload');

// 读取本地缓存的用户配置
store.commit('settings/init');

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
