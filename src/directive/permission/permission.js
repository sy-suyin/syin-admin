import store from '@/vuex/store'
import {isEmpty, isSet} from '@/libs/util.js';

export default {
	inserted(el, binding, vnode) {
		const { value, arg } = binding

		let type = 'data';

		let user = store.getters['auth/user'];

		// 默认为页面权限
		if(arg == '' || arg != 'data'){
			type = 'page';
		}

		if(isEmpty(value) || value.length != 2){
			throw new Error(`need roles! Like v-permission="['admin','index']"`)
		}

		let forbid_list = store.getters[`access/${type}_forbid`];
		let controller = value[0];
		let action = value[1];

		if(!user){
			el.parentNode && el.parentNode.removeChild(el)
		}

		if(!!user.is_admin){
			return true;
		}

		if(isEmpty(forbid_list)){
			return true;
		}

		if(! isSet(forbid_list, controller)){
			return true;
		}

		if(-1 == forbid_list[controller].indexOf(action)){
			return true;
		}

		el.parentNode && el.parentNode.removeChild(el)
	}
}
