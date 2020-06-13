<template>
	<div>
		<component :is="error_page" :msg="tip_msg"></component>
	</div>
</template>

<script>
import error_403 from "@/views/error/403";
import error_404 from "@/views/error/404";
import error_500 from "@/views/error/500";

export default {
	name: 'error-page',
	components: {
		error_403, error_404, error_500
	},
	props: {
		info: {
			type: Object,
			default: function () {
				return {
					type: '',
					title: '',
					msg: '',
				}
			}
		}
	},
	data(){
		return {
			error_page: '',
			tip_msg: '',
		}
	},
	mounted(){
		let {
			type = 'miss_page',
			title = '',
			msg = '',
		} = this.info;

		let error_type = type;

		// 当需加载的异常页面不是 403, 404, 500 页面时, 判断是加载403还是404页面 
		if(-1 == [403, 404, 500].indexOf(type)){
			// 对于无权限访问的页面, 应展示禁止访问
			let blocklist = this.$store.getters['access/blocklist']['page'];
			let path = this.$route.path;
			let can_access = true;
	
			path = path.split('/');
			let path_len = path.length;
	
			if(path_len >= 2){
				let controller = path[path_len - 2].trim();
				let action = path[path_len - 1].trim();
	
				if(blocklist.hasOwnProperty(controller) && 
					blocklist[controller].indexOf(action) != -1
				){
					// 无权访问页面
					can_access = false;
				}
			}

			error_type = can_access ? 404 : 403;
		}

		// 设置浏览器标题
		window.document.title = title || error_type;

		// 设置需加载的异常页面模板
		this.error_page = `error_${error_type}`;

		// 设置提示消息
		msg != '' && (this.tip_msg = msg);
	}
}
</script>

<style>

</style>