/**
 * 新菜单处理方法
 */

import * as Util from '@/libs/util.js';

/**
 * 组件基类
 */
class Component{

	// 子组件列表
	components = [];

	/**
	 * 添加子组件, 传入的数据必须为继承自该类的实例
	 *
	 * @param Component com
	 *
	 * @returns int 返回插入的实例在列表中的索引
	 */
	add(com){
		this.components.push(com);
		return this.components.length - 1;
	}

	/**
	 * 从组件列表中移除子组件
	 *
	 * @param int index 子组件在列表中的位置索引
	 */
	remove(index){
		this.components.splice(index, 1);
	}

	/**
	 * 获取子组件列表长度
	 */
	length(){
		return this.components.length;
	}

	/**
	 * 清空子组件列表
	 */
	clear(){
		delete this.component;
		this.components = [];
	}

	/**
	 * 循环子组件列表
	 *
	 * @param function fn 循环处理方法, 传入参数为 [子组件, 位置索引]
	 *
	 */
	each(fn){
		for(let i = 0, len = this.components.length; i < len; i++){
			if(false === fn(this.components[i], i)){
				break;
			}
		}
	}
}

/**
 * 菜单类接口
 */
class MenuInterface extends Component{

	constructor(){
		super();
	}

	/**
	 * 激活菜单
	 *
	 * @param string controller 控制器,模板所在文件夹
	 * @param string action  	方法, 模板文件名
	 */
	active(controller, action){}

	/**
	 * 生成动态路由所需的数据
	 */
	routers(){}

	/**
	 * 生成左侧侧边栏所需的数据
	 */
	menus(){}

	/**
	 * 销毁自身菜单组件
	 */
	destroy(){}
}

/**
 * 菜单管理
 */
export default class Menu extends MenuInterface{

	constructor(config){
		super();

		config && this.init(config);
	}

	/**
	 * 初始化菜单组件
	 *
	 * @param array config 菜单配置
	 */
	init(config){
		config.forEach((item, index) =>{
			if(! Util.checkPermission(item.controller, item.action, 'page')){
				return;
			}

			let com = new MenuItem(item);
			if(com instanceof MenuInterface){
				this.add(com);
			}
		});
	}

	active(controller, action){
		let results = [];
		this.each((item, index)=>{
			let result = item.active(controller, action);
			if(!result){
				return;
			}

			// 使返回的数据始终为数组
			if(Util.getType(result) != 'array'){
				results.push(result);
			}else{
				results = result;
			}
		});
		return results;
	}

	menus(){
		let results = [];
		this.each((item, index)=>{
			let result = item.menus();
			if(result){
				results.push(result);
			}
		});
		return results;
	}

	routers(){
		let routers = [];
		this.each((item, index)=>{
			let router = item.routers();
			if(router){
				if(Util.getType(router) == 'array'){
					routers.push(...router);
				}else{
					routers.push(router);
				}
			}
		});

		// 注: 返回的路由为二级路由, 需补零级路由和一级路由(需要传参数的仅可为二级路由)
		if(routers.length){
			let mapping = {};
			let has_root = false;

			routers.forEach(item => {
				let controller = item.meta.controller;

				if( item.meta.params == '' && !Util.isSet(mapping, controller)){
					let action = item.meta.action;
					mapping[controller] = 1;

					if(!has_root){
						has_root = true;

						// 添加零级路由导航
						routers.push({
							path: `/`,
							redirect: `/${controller}/${action}`,
						});
					}

					// 添加一级路由导航
					routers.push({
						path: `/${controller}`,
						redirect: `/${controller}/${action}`,
					});
				}
			});

			// 添加404页面
			routers.push({
				path: `/*`,
				name: 'not_fonund',
				component: () => import( `../views/error/404.vue`),
			});
		}


		return routers;
	}

	destroy(){
		this.each((item, index)=>{
			item.destroy();
		});
		this.clear();
	}
}

/**
 * 子菜单
 */
class MenuItem extends MenuInterface{

	// 子菜单配置
	#config

	constructor(config){
		super();

		config.params = config.params || '';
		this.config = {...config};
		this.config.key = config.controller + '-' + config.action;
		delete this.config.children;

		if(config.hasOwnProperty('children') && config.children.length > 0){
			config.children.forEach((item, index) =>{

				if(! Util.checkPermission(item.controller, item.action, 'page')){
					return;
				}

				let com = new MenuItem(item);

				// 修改当有下级，下级菜单因无权限访问而导致异常的问题. 1
				if(com instanceof MenuInterface){
					this.add(com);
				}
			});

			// 修改当有下级，下级菜单因无权限访问而导致异常的问题. 2
			if(this.length() < 1){
				return {};
			}
		}
	}

	active(controller, action){

		// 初始化
		this.config.is_active = false;

		if(this.length()){
			let results = [];
			this.config.is_open = false;

			this.each((item, index)=>{
				let result = item.active(controller, action);
				let first_result = result;

				if(result){
					let result_type = Util.getType(result);
					if(result_type == 'array'){
						first_result = result[0];
					}

					if(!!first_result.is_hidden && Util.isSet(first_result, 'relation') && first_result.relation != ''){
						// 将关联的也设置为已激活
						this.each((brother, index)=>{
							brother.relationActive(first_result.relation);
						});
					}

					this.config.is_active = true;
					this.config.is_open = true;
					results.push(this.config);
					if(result_type == 'array'){
						results.push(...result);
					}else{
						results.push(result);
					}

					// return false;
				}
			});

			if(results.length < 1){
				return false;
			}else{
				return results;
			}
		}else{
			if(controller == this.config.controller && action == this.config.action){
				this.config.is_active = true;
				return this.config;
			}

			return false;
		}
	}

	/**
	 * 将关联的兄弟子菜单设置为选中激活状态
	 * 
	 * @param string key 
	 */
	relationActive(key){
		if(key == this.config.key && this.config.is_hidden == 0){
			this.config.is_active = true;
			return this.config;
		}

		return false;
	}

	menus(){
		// 赋值以避免计算对自身数据造成污染
		let config = {...this.config};

		if(this.length()){
			let results = [];
			this.each(item => {
				let result = item.menus();
				if(result){
					results.push(result);
				}
			});

			if(results.length < 1){
				return false;
			}

			// 为方便侧边栏操作, 当只有一项子菜单时, 将下一级菜单归并到上一级菜单
			if(results.length == 1 && results[0].children.length < 1){
				let next = results[0];

				next.name = config.name + ' - '+next.name;
				config = next;

			}else{
				config.children = results;
			}

			return config;
		}else{
			if(this.config.is_hidden){
				return false;
			}

			config.children = [];
			return config;
		}
	}

	routers(){
		if(this.length()){
			let routers = [];
			this.each((item, index)=>{
				let router = item.routers();
				if(router){
					if(Util.getType(router) == 'array'){
						routers.push(...router);
					}else{
						routers.push(router);
					}
				}
			});
			return routers;
		}else{
			let controller = this.config.controller;
			let action = this.config.action;
			let params = this.config.params || '';

			let router = {
				path: `/${controller}/${action}${params}`,
				name: `${controller}_${action}`,
				component: () => import( `../views/${controller}/${action}.vue`),
				meta: {
					controller: controller,
					action: action,
					params: params,
					title: this.config.name
				}
			}

			return router;
		}
	}

	destroy(){
		if(this.length()){
			this.each((item, index)=>{
				item.destroy();
			});
		}

		this.clear();
	}
}