import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import menu from '@/libs/menu.js';

Vue.use(VueRouter)

const routes = [
	{
		path: '/',
		name: 'root',
		redirect: '/index',
	},
	{
		path: '/index',
		name: 'index',
		component: Home
	},
	{
		path: '/login',
		name: 'login',
		component: () => import(/* webpackChunkName: "about" */ '../views/login.vue')
	},
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

		if(['login','register'].findIndex((value)=>{return value==path}) !== -1){
			next({
				replace: true,
				name: 'index'
			});
		}else{
			next();			
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
