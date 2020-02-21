<template>
	<div>
		<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>

		<el-breadcrumb-item>活动管理</el-breadcrumb-item>
		<el-breadcrumb-item>活动列表</el-breadcrumb-item>
		<el-breadcrumb-item>活动详情</el-breadcrumb-item>
		<sy-menu-item></sy-menu-item>
	</div>
</template>

<script>
import syMenuItem from "@/components/layout/sy-menu-item.vue";

export default {
	name: 'sy-breadcrumb',
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