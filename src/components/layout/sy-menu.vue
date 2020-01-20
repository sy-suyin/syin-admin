<template>
	<div>
		<sy-menu-item></sy-menu-item>
	</div>
</template>

<script>
import syMenuItem from "@/components/layout/sy-menu-item.vue";

export default {
	name: 'sy-menu',
	components: {
		syMenuItem
	},
	data(){
		return {
		}
	},
	created(){
		let meta = this.$route.meta;

		// 设置浏览器标题
		window.document.title = meta.title;
		
		// 激活路由
		this.$store.commit('access/active',meta);
	}
}
</script>