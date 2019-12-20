let routers= [];

/** 
 * 私有方法 - 菜单配置递归
 */
let menuRecursive = function(menu, blacklist = {}){

	if(menu.hasOwnProperty('children') && menu.children.length > 0){
		// 在侧边栏中能显示的子菜单数
		let show_submenu_count = 0;

		menu.is_open = 1;
		menu.children.forEach((submenu, key) => {
			submenu = menuRecursive(submenu, blacklist);

			// 当子菜单也有子菜单且全部不能显示时, 删除该子菜单
			if(false == submenu){
				menu.children.splice(key , 1);
				return;
			}

			if(submenu.hasOwnProperty('is_active') && submenu.is_active > 0){
				menu[key].is_active = 1;
			}

			// 当下一级菜单无权限访问时, 从数据里删除
			if(submenu.hasOwnProperty('is_ban') && submenu.is_ban > 0){
				menu.children.splice(key , 1);
				return;
			}

			// 当下一级因无权限被禁止是上级一起删除
			if( ! submenu.hasOwnProperty('is_hidden') || submenu.is_hidden < 1 ){
				show_submenu_count += 1;
			}
		});

		if(show_submenu_count < 1){
			return false;
		}

		/* if(show_submenu_count == 1){
			menu.children.forEach((submenu, key) => {
				if(submenu.hasOwnProperty('is_hidden') && submenu.is_hidden > 0){
					return;
				}

				submenu.name = menu.name + ' - ' + submenu.name;
				submenu.is_open = menu.is_open;

				menu = submenu;
			});
		} */
	}else{
		// 判断路由是否在黑名单中
		if(blacklist.hasOwnProperty(menu.controller) 
			&& blacklist[menu.controller].indexOf(menu.action) != -1){

			menu['is_ban'] = 1;
			return menu;
		}

		// todo: 此处判断当前路由

		// 此处将该菜单记入路由
		routers.push({
			controller: menu.controller,
			action: menu.action,
		});
	}

	return menu;
}

let calcMenu = function(menus, blacklist = {}){
	routers = [];

	menus.forEach((menu, key) => {
		menu = menuRecursive(menu, blacklist);

		if(false == menu){
			menus.splice(key , 1);
		}
	});

	return menus;
}

let getMenus = function(blacklist){
	// import menus from '';

	// todo: 保留上一次的菜单状态


	return import('@/config/menu').then((module)=>{
		let menus = module.menus;
		
		return new Promise((resolve, reject) => {
			menus = calcMenu(menus, blacklist);
			resolve(menus);
		});
	});
}

export default {
	getMenus
}