import {menus as configs} from '@/config/menu';
import Storage from '@/libs/Storage.js';
import Menu from '@/libs/Menu';

// 菜单计算类实例
let MenuInstance = new Menu;

const state = {
	blocklist: {
		data: {},
		page: {},
	},

	// 是否已对菜单文件进行过计算
	is_calc: false,

	// 路由
	routers: [], 

	// 菜单
	menus: [],

	// 导航栏面包屑数据
	breadcrumb: [],
}

const getters = {
	blocklist: state=>state.blocklist,
	is_calc: state=>state.is_calc,
	routers: state=>state.routers,
	menus: state=>state.menus,
	breadcrumb: state=>state.breadcrumb,
}

const mutations = {

	/*
	 * 激活菜单
	 */
	active(state, payload){
		let breadcrumb = MenuInstance.active(payload.controller, payload.action);
		let menus = MenuInstance.menus();
		state.menus = menus;
		state.breadcrumb = breadcrumb;
	},

	/*
	 * 重新加载, 从缓存中读取数据
	 */
	reload(state){
		let blocklist = Storage.get('blocklist', {json: true});

		if(blocklist){
			state.blocklist = blocklist;
		}

		this.dispatch('access/calc');
	},

	/*
	 * 设置页面黑名单和数据黑名单
	 */
	set(state, blocklist){
		state.blocklist = blocklist;

		Storage.set('blocklist', blocklist);
		this.dispatch('access/calc');
	},
}

const actions = {
	/*
	 * 根据菜单配置进行计算
	 */
	async calc({state, rootGetters}){
		let user = rootGetters['auth/user'];

		if(!user || state.is_calc){
			return;
		}

		MenuInstance.init(configs);

		state.is_calc = true;
		state.routers = MenuInstance.routers();
		state.menus = MenuInstance.menus();
	}
}

export default {
	namespaced: true,
    state,
    getters,
	mutations,
	actions
}