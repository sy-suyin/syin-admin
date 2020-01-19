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

		if(! store.state.access.is_calc){
			store.commit('access/calc');
			let router_configs = store.state.access.routers;

			is_calc = true;
			router.addRoutes(router_configs);
		}

		if(['login','register'].findIndex((value)=>{return value==path}) !== -1){
			next({
				replace: true,
				name: 'index'
			});
		}else{
			if(is_calc){
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
