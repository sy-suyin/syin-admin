<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">角色列表</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
			<db-table 
				:data="results"
				:columns="columns"
				:actionbar="actionbar"
				:pagination="page_default"
				:toolbar="toolbar"
				@handle="handle"
			>
			</db-table>
		</div>
	</div>
</template>

<script>
import pageMixin from "@/mixins/page";
import tableMixin from "@/mixins/table";
import commonMixin from "@/mixins/common";

export default {
	name: "system_rolerecycle",
	mixins: [pageMixin, tableMixin, commonMixin],
  	data() {
      	return {
			// 各跳转链接
			urls: {
				add: '/system/roleadd',
				del: '/system/roledel',
				dis: '/system/roledis',
				edit: '/system/roleedit/:id',
				list: '/system/rolelist',
				recycle: '/system/rolerecycle',
			},

			columns: [
				{
					prop: 'selection',
				},
				{
					prop: 'id',
					label: '编号',
					width: 60,
				},
				{
					prop: 'name',
					label: '角色名称',
					width: 200,
				},
				{
					prop: 'add_time',
					label: '添加时间',
					width: 160,
					formatter: this.filterTime
				}
			],

			actionbar: [
				{
					type: 'btn',
					name: '还原',
					target: 'del',
					access: ['system', 'roledel'],
					params: {
						operate: 0,
					}
				},
			],

			toolbar: [
				{
					type: 'btn',
					name: '刷新',
					target: 'reload',
					icon: "el-icon-refresh-left",
					color: 'primary',
				},
				{
					type: 'url',
					name: '列表',
					target: 'list',
					color: 'primary',
					icon: "el-icon-s-promotion",
					access: ['system', 'rolelist'],
				},
				{
					type: 'btn',
					name: '还原',
					target: 'del',
					color: 'success',
					icon: "el-icon-delete",
					access: ['system', 'roledel'],
					params: {
						operate: 0,
					}
				},
			]
		}
	},
	created(){
		this.addScene(this.urls.recycle, 'default', { mapping: 'results' });
	},
	mounted(){
		this.getRequestData();
	},
	methods: {
    }
};
</script>

<style lang="scss">
@import "@/assets/style/table.scss";
</style>