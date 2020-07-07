import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import routers from './router';

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'hash',
	routes: routers
})

// 是否已添加动态路由
let is_router_add = false;
// 未登录时允许直接访问的页面
const NOT_LOGGED_PAGES = ['login', 'register'];

router.beforeEach((to, from, next) => {
	let is_logged = !!store.state.auth.user;

	if(is_logged){
		if(!is_router_add){
			// 在此处动态添加路由

			if(! store.state.access.is_calc){
				store.commit('access/reload');
			}

			router.addRoutes(store.state.access.routers);
			is_router_add = true;

			// 在初次添加路由时, 此写法能直接跳转到正确页面
			next({ ...to, replace: true });
			return;
		}

		if(to.name && NOT_LOGGED_PAGES.includes(to.name)){
			// 在已登录的情况下, 访问登录注册页面时, 跳转到后台首页 
			next({
				replace: true,
				path: store.state.access.routers[0].path
			});
		} else {
			next();
		}
	}else {
		if(NOT_LOGGED_PAGES.includes(to.name)){
			// 如果在未登录允许访问的页面中, 直接进入
			next();
		}else{
			// 触发退出登录
			// store.commit('auth/logout');
			next({
				replace: true,
				name: 'login'
			});
		}
	} 
});

router.afterEach((to) => {
    window.scrollTo(0, 0);
});

export default router
