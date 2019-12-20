import VueRouter from 'vue-router'


let getMenus = function(blacklist){
	// import menus from '';

	// todo: 保留上一次的菜单状态
	
	console.log(VueRouter);
	
	let routers = [];

	/** 
	 * 私有方法 - 菜单配置递归
	 */
	let menuDeal = function(menu, blacklist){
		if(menu.hasOwnProperty('children') && menu.children.length > 0){
			menu.is_open = 1;

			menu.children.forEach((submenu, key) => {
				submenu = menuDeal(submenu, blacklist);
	
				if(submenu.hasOwnProperty('is_active') && submenu.is_active > 0){
					menu[key].is_active = 1;
				}

				// 当下一级因无权限被删除是上级一起删除
				/* if(submenu.hasOwnProperty('is_deleted') && submenu.is_deleted > 0){
					menu[key].is_deleted = 1;
				}*/
			});
		}else{
			// 此处将该菜单记入路由
			routers.push({
				controller: menu.controller,
				action: menu.action,
			});
		}
	
		return menu;
	}

	import('@/config/menu').then((module)=>{
		let menus = module.menus;
		console.log(menus);

		menus.forEach(menu => {
			menu = menuDeal(menu);
		});

	});


}

export default {
	getMenus
}