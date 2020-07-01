<template>
	<div id="pagination">
		<el-pagination
			@size-change="handle('sizeChange', $event)"
			@current-change="handle('pageChange', $event)"
			:current-page="pagination.current"
			:page-sizes="[10, 20, 30, 50]"
			:page-size="pagination.num"
			layout="total, sizes, prev, pager, next, jumper"
			:total="pagination.total">
		</el-pagination>
	</div>
</template>

<script>

export default {

	props: {
		pagination: Object,
	},

	methods: {
		handle(...params){
			params.unshift('handle');
			this.$emit.apply(this, params);
		}
	}
}
</script>