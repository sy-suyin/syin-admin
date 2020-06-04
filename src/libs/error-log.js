import Vue from 'vue'

Vue.config.errorHandler = function(err, vm, info, a) {
	console.log(err);
	console.log(vm);
	console.log(info);
	console.log(a);
	// Vue.nextTick(() => {
	// 	store.dispatch('errorLog/addErrorLog', {
	// 	  err,
	// 	  vm,
	// 	  info,
	// 	  url: window.location.href
	// 	})
	// 	console.error(err, info)
	// })
}