import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
	{
		path: '/',
		name: 'root',
		redirect: '/index',
	},
	{
		path: '/index',
		name: 'home',
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

router.beforeEach((to, from, next) => {
	let is_logged = !!localStorage.getItem('user');
	let path = to.path;

	path = path ? path.substr(1) : '';

	if(is_logged){
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
