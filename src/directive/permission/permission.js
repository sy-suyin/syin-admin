import store from '@/vuex/store'

export default {
	inserted(el, binding, vnode) {
		const { value, arg } = binding
		// const roles = store.getters && store.getters.roles

		let type = 'data';

		// 默认为页面权限
		if(arg == '' || arg != 'data'){
			type = 'page';
		}

		let user = store.getters['auth/user'];
		console.log(user);

		if(!user){
			return false;
		}

		if(!!user.is_admin){
			return true;
		}

		let forbid_list = store.getters[`access/${type}_forbid`];

		if(!forbid_list || forbid_list.length < 1){
			return true;
		}

		console.log(forbid_list);

		// 1. 获取用户信息, 如果是超级管理员则默认有最高级权限

		// 2. 获取对应名单


		// if (value && value instanceof Array && value.length > 0) {
		// const permissionRoles = value

		// const hasPermission = roles.some(role => {
		// 	return permissionRoles.includes(role)
		// })

		// if (!hasPermission) {
		// 	el.parentNode && el.parentNode.removeChild(el)
		// }
		// } else {
		// 	throw new Error(`need roles! Like v-permission="['admin','index']"`)
		// }
	}
}
