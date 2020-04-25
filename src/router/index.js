import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../vuex/store'
import routers from './router';

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	base: process.env.BASE_URL,
	routes: routers
})

// 是否已添加动态路由
let is_router_add = false;
// 
const NOT_LOGGED_PAGES = ['login', 'register'];

router.beforeEach((to, from, next) => {
	let is_logged = !!store.state.auth.currentUser;
	let path = to.path;

	path = path ? path.substr(1) : '';

	if(is_logged){
		// 在此处动态添加路由
		// is_calc 用于next修正路由后, 解决第二次路由加载时又会修正的问题
		let is_calc = false;
		let router_configs = store.state.access.routers;

		if(!is_router_add){
			if(! store.state.access.is_calc){
				store.commit('access/reload');
			}

			router.addRoutes(router_configs);
			is_router_add = is_calc = true;
		}

		// 在已登录的情况下, 访问登录注册页面时, 跳转到后台首页 
		if(NOT_LOGGED_PAGES.findIndex((value)=>{return value==path}) !== -1){
			next({
				replace: true,
				path: router_configs[0].path
			});
		}else{
			if(is_calc){
				// 在初次添加路由时, 此写法能直接跳转到正确页面
				next({ path: to.path });
			}else{
				next();
			}
		}
	}else if(NOT_LOGGED_PAGES.findIndex((value)=>{return value==path}) == -1){
		console.log(path);
		// 在未登录的情况下, 访问非登录允许访问的页面时, 跳转回登录页面
		next({
			replace: true,
			name: 'login'
		});
	}

	next();
});

router.afterEach((to) => {
    window.scrollTo(0, 0);
});

export default router
