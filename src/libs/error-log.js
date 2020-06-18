import Vue from 'vue'
import store from '@/store'

Vue.config.errorHandler = function(err, vm, info, a) {
	console.log(err);

	if(vm){
		info = vm.$vnode.tag + ' error in ' + info;
	}

	Vue.nextTick(() => {
		store.dispatch('errorLog/addErrorLog', {
		  err,
		  info,
		  url: window.location.href
		})
	});
}