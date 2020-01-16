import {menus as configs} from '@/config/menu';
import Vue from 'vue'

const data = {
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

	// 已激活的菜单
	menus_active: [],
}

const getters = {
	data_forbid: state=>state.data_forbid,
	menus: state=>state.menus
}

const mutations = {

	// 激活菜单
	active(state, payload){

	},

	// 重新加载, 从缓存中读取数据
	reload(){

	},

	set(){

	},

	calc(state){

	}
}

export default {
    state:data,
    getters,
    mutations
}