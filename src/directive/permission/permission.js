import store from '@/store';
import {isEmpty, isSet} from '@/libs/util';

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

		let blocklist = store.getters[`access/blocklist`][type];
		let controller = value[0];
		let action = value[1];

		if(!user){
			el.parentNode && el.parentNode.removeChild(el)
		}

		if(!!user.is_admin){
			return true;
		}

		if(isEmpty(blocklist)){
			return true;
		}

		if(! isSet(blocklist, controller)){
			return true;
		}

		if(-1 == blocklist[controller].indexOf(action)){
			return true;
		}

		el.parentNode && el.parentNode.removeChild(el)
	}
}
