<template>
	<div class="page-header">
		<slot name="breadcrumb">
			<el-breadcrumb class="breadcrumb" separator-class="el-icon-arrow-right">
				<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
				<el-breadcrumb-item v-for="(breadcrumb, index) in breadcrumbs" :key="'breadcrumb-'+index">{{breadcrumb.name}}</el-breadcrumb-item>
			</el-breadcrumb>
		</slot>

		<slot name="breadcrumb-after">
		</slot>
	</div>
</template>

<script>
export default {
	data(){
		return {
			breadcrumbs: [],
		}
	},
	mounted(){
		// 设置面包屑数据
		this.breadcrumbs = this.$store.getters['access/breadcrumb'];
	}
}
</script>

<style lang="scss" scoped>
.page-header{
	padding: 0 20px;
	background: #fff;

	.breadcrumb{
		padding: 12px 16px;
		padding-left: 0;
	}
}
</style>