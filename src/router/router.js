// 登录页面
const loginRouter = {
	path: '/login',
	name: 'login',
	meta: {
		title: '登录'
	},
	component: () => import('../views/pages/login.vue')
};

// 锁屏
const lockRouter = {
	path: '/lock/',
	name: 'lock',
	meta: {
		title: '锁屏'
	},
	component: () => import('../views/pages/lock.vue')
};

// 所有上面定义的路由都要写在下面的routers里
export default [
	loginRouter,
	lockRouter
];