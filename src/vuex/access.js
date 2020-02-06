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

	// 导航栏面包屑数据
	breadcrumb: []
}

const getters = {
	data_forbid: state=>state.data_forbid,
	page_forbid: state=>state.page_forbid,
	is_calc: state=>state.is_calc,
	routers: state=>state.routers,
	menus: state=>state.menus,
	breadcrumb: state=>state.breadcrumb,
}

const mutations = {

	// 激活菜单
	active(state, payload){
		function deepClone(obj){
			let objClone = Array.isArray(obj)?[]:{};
			if(obj && typeof obj==="object"){
				for(let key in obj){
					if(obj.hasOwnProperty(key)){
						//判断ojb子元素是否为对象，如果是，递归复制
						if(obj[key]&&typeof obj[key] ==="object"){
							objClone[key] = deepClone(obj[key]);
						}else{
							//如果不是，简单复制
							objClone[key] = obj[key];
						}
					}
				}
			}
			return objClone;
		}

		let menus = [state.menus[0], deepClone(state.menus[1]), deepClone(state.menus[2]), state.menus[3]];
		let active_controller = payload.controller;
		let active_action = payload.action;
		let breadcrumb = [];

		for(let len = menus.length,i = len - 1;i >= 0;i--){
			let level_menus = menus[i];

			if(i == 0){
				level_menus.forEach((item, index) =>{
					if(item.controller == active_controller && item.action == active_action){
						if(item.has_children){
							menus[i][index].is_open = true;
						}

						menus[i][index].is_active = true;
						breadcrumb.unshift(item);
					}else if(item.has_children){
						menus[i][index].is_open = false;
					}
				});
			}else{
				for(let key in level_menus){
					level_menus[key].forEach((item, index) =>{
						if(item.controller == active_controller && item.action == active_action){
							let key_arr = key.split('_');
							active_controller = key_arr[0];
							active_action = key_arr[1];

							if(item.has_children){
								menus[i][key][index].is_open = true;
							}

							menus[i][key][index].is_active = true;
							breadcrumb.unshift(item);
						}else if(item.has_children){
							menus[i][key][index].is_open = false;
							// menus[i][key][index].is_active = false;
						}else{
							// menus[i][key][index].is_active = false;
						}
					});
				}
			}
		}

		state.menus_active = menus;
		state.breadcrumb = breadcrumb;
	},

	// 重新加载, 从缓存中读取数据
	reload(){
		let data_forbid = localStorage.getItem('data_forbid');
		let page_forbid = localStorage.getItem('page_forbid');

		if(data_forbid && page_forbid){
			data_forbid = JSON.parse(data_forbid);
			page_forbid = JSON.parse(page_forbid);

			state.data_forbid = data_forbid;
			state.page_forbid = page_forbid;
		}

		this.commit('access/calc');
	},

	// 设置页面黑名单和数据黑名单
	set(state, payload){
		state.data_forbid = payload.data_forbid;
		state.page_forbid = payload.page_forbid;

		localStorage.setItem('data_forbid',JSON.stringify(payload.data_forbid));
		localStorage.setItem('page_forbid',JSON.stringify(payload.page_forbid));

		this.commit('access/calc');
	},

	// 根据菜单配置进行计算
	calc(state){
		let cur_level = 0;
		let next = {};
		let routers = [];
		let menus = [];
		let blacklist = state.page_forbid;
		let menu_router = [];

		if(state.is_calc){
			return;
		}

		next['/'] = configs;

		do{
			let keys = Object.keys(next);
			let data = {...next};
			menus[cur_level] = {};
			next = {};

			keys.forEach(key => {
				let val = data[key];
				menus[cur_level][key] = [];

				val.forEach(item => {
					if(item.hasOwnProperty('children') && item.children.length){

						// 有子节点
						item.has_children = true;

						next[item.controller + '_' + item.action] = item.children;
						menus[cur_level][key].push(item);
					}else{
						// 没有子节点
						item.has_children = false;

						// 判断权限, 并将自身加入路由数组
						if(blacklist.hasOwnProperty(item.controller) && blacklist[item.controller].indexOf(item.action) != -1){
							return;
						}

						if(!item.is_hidden){
							menus[cur_level][key].push(item);		
						}

						routers.push(item);

						!menu_router.hasOwnProperty(item.controller) && (menu_router[item.controller] = {});
						menu_router[item.controller][item.action] = 1;
					}
				});

				// 当子菜单全部不符合要求时, 清除该项
				menus[cur_level][key].length < 1 && delete menus[cur_level][key];
			})
			cur_level += 1;
		}while( Object.keys(next).length);

		// 清除无下级的菜单
		for(let max_index = menus.length - 1, index = max_index;index >= 0;index--){
			let level_menu = menus[index];
			let keys = Object.keys(level_menu);

			keys.forEach(key => {
				level_menu[key].forEach( (item, item_key) => {
					let children_key = item.controller + '_' + item.action;

					if(item.has_children && ( index == max_index || !(menus[index + 1].hasOwnProperty(children_key)) ) ){
						menus[index][key].splice(item_key, 1);
						menus[index][key].length < 1 && delete menus[index][key];
					}
				})
			});

			if(Object.keys(menus[index]).length < 1){
				max_index -= 1;
				menus.splice(index , 1);
			}
		}

		// 整理路由格式
		{
			let routers_temp = [];
			let mapping = {};

			routers.forEach(router => {
				// 添加二级路由导航
				routers_temp.push({
					path: `/${router.controller}/${router.action}`,
					name: `${router.controller}_${router.action}`,
					component: () => import( `../views/${router.controller}/${router.action}.vue`),
					meta: {
						controller: router.controller,
						action: router.action,
						title: router.name
					}
				});

				// 添加零级路由导航
				if(routers_temp.length < 1){
					routers_temp.push({
						path: `/`,
						redirect: `/${router.controller}/${router.action}`,
					});
				}

				// 添加一级路由导航
				if( !mapping.hasOwnProperty(router.controller) 
					&& menu_router.hasOwnProperty(router.controller) 
					&& menu_router[router.controller].hasOwnProperty(router.action) ){
					routers_temp.push({
						path: `/${router.controller}`,
						redirect: `/${router.controller}/${router.action}`,
					});

					mapping[router.controller] = router.action;
				}
			});

			// 添加404页面
			routers_temp.push({
				path: `/*`,
				name: 'error_404',
				component: () => import( `../views/error/404.vue`),
			});

			routers = routers_temp;
		}

		menus[0] = menus[0]['/'];

		state.is_calc = true;
		state.routers = routers;
		state.menus = menus;
	}
}

export default {
	namespaced: true,
    state:data,
    getters,
    mutations
}