import {menus as configs} from '@/config/menu';
import Menu from '@/libs/menu';
import Vue from 'vue';

// let MenuClass = new Menu(configs);
// MenuClass.active('system', 'adminadd');

// 菜单计算类实例
let MenuInstance = new Menu;

const state = {
	// 数据访问黑名单
	data_forbid: {},

	// 页面菜单黑名单
	page_forbid: {},

	// 是否已对菜单文件进行过计算
	is_calc: false,

	// 路由
	routers: [], 

	// 菜单
	menus: [],

	// 导航栏面包屑数据
	breadcrumb: [],

	// 页面标题
	page_title: '',
}

const getters = {
	data_forbid: state=>state.data_forbid,
	page_forbid: state=>state.page_forbid,
	is_calc: state=>state.is_calc,
	routers: state=>state.routers,
	menus: state=>state.menus,
	breadcrumb: state=>state.breadcrumb,
	page_title: state=>state.page_title,
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
		state.page_title = payload.title;
	},

	/*
	 * 重新加载, 从缓存中读取数据
	 */
	reload(state){
		let data_forbid = localStorage.getItem('data_forbid');
		let page_forbid = localStorage.getItem('page_forbid');

		if(data_forbid && page_forbid){
			data_forbid = JSON.parse(data_forbid);
			page_forbid = JSON.parse(page_forbid);

			state.data_forbid = data_forbid;
			state.page_forbid = page_forbid;
		}

		this.dispatch('access/calc');
	},

	/*
	 * 设置页面黑名单和数据黑名单
	 */
	set(state, payload){
		state.data_forbid = payload.data_forbid;
		state.page_forbid = payload.page_forbid;

		localStorage.setItem('data_forbid',JSON.stringify(payload.data_forbid));
		localStorage.setItem('page_forbid',JSON.stringify(payload.page_forbid));

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