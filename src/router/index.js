import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import menu from '@/libs/menu.js';
import store from '../vuex/store'

Vue.use(VueRouter)

const routes = [
	{
		path: '/',
		name: 'root',
		redirect: '/login',
	},
	{
		path: '/login',
		name: 'login',
		component: () => import('../views/login.vue')
	},
	// {
	// 	path: '/test/form',
	// 	name: 'test_form',
	// 	component: () => import('../views/test/form.vue')
	// },
]

const router = new VueRouter({
	mode: 'history',
	base: process.env.BASE_URL,
	routes
})

let is_router_add = false;

router.beforeEach((to, from, next) => {
	let is_logged = !!localStorage.getItem('currentUser');
	let path = to.path;

	path = path ? path.substr(1) : '';

	if(is_logged ){
		// 在此处动态添加路由
		let is_calc = false;
		let router_configs = store.state.access.routers;	

		if(!is_router_add){
			if(! store.state.access.is_calc){
				store.commit('access/reload');
			}
			
			router.addRoutes(router_configs);
			is_router_add = is_calc = true;
		}

		if(['login','register'].findIndex((value)=>{return value==path}) !== -1){
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
	}else if(['login','register'].findIndex((value)=>{return value==path}) == -1){
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
