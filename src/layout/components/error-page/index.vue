<template>
	<div>
		<error_404 v-if="can_access"></error_404>
		<error_403 v-else></error_403>
	</div>
</template>

<script>
import error_403 from "@/views/error/403";
import error_404 from "@/views/error/404";

export default {
	name: 'error-page',
	components: {
		error_403, error_404
	},
	data(){
		return {
			can_access: false
		}
	},
	mounted(){
		// 对于无权限访问的页面, 应展示禁止访问
		let page_forbid = this.$store.getters['access/page_forbid'];
		let path = this.$route.path;
		let can_access = true;


		path = path.split('/');
		let path_len = path.length;

		if(path_len >= 2){
			let controller = path[path_len - 2].trim();
			let action = path[path_len - 1].trim();

			if(page_forbid.hasOwnProperty(controller) && 
				page_forbid[controller].indexOf(action) != -1
			){
				// 无权访问页面
				can_access = false;
			}
		}

		this.can_access = can_access;

		// 设置浏览器标题
		window.document.title = can_access ? 404 : 403;
		console.log(window.document.title);
	}
}
</script>

<style>

</style>