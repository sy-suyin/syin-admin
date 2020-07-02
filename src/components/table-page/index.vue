<template>
	<div id="pagination">
		<el-pagination
			@size-change="handle('sizeChange', $event)"
			@current-change="handle('pageChange', $event)"

			:current-page="pagination.current"
			:page-size="pagination.num"
			:total="pagination.total"

			:page-sizes="params.sizes"
			:layout="params.layout"
		>
		</el-pagination>
	</div>
</template>

<script>

export default {
	props: {
		pagination: Object,
		config: Object,
	},
	data(){
		return {
			params: null
		}
	},
	created(){
		const def_config = {
			sizes: [10, 20, 30, 50],
			layout: 'total, sizes, prev, pager, next, jumper',
			scene: '',
		};

		this.params = {
			...def_config,
			...this.config
		};
	},
	methods: {
		handle(...params){
			params.unshift('handle');
			params.push(this.params.scene);
			this.$emit.apply(this, params);
		}
	}
}
</script>