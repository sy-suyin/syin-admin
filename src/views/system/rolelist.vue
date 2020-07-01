<template>
	<div class="table-base">
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
				:operates="operates"
				:pagination="page_default"
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
import dbTable from "@/components/db-table";

export default {
	name: "system_rolelist",
	mixins: [commonMixin, pageMixin, tableMixin],
	components: { dbTable },
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
					slot: 'disabled',
				},
				{
					prop: 'disabled',
					label: '状态',
					tags: [
						{
							prop: 'is_disabled',
							class: 'disabled-btn',
							access: {
								controller: 'system',
								action: 'roledis',
								type: 'data'
							},
							data: {
								0: {
									val: '启用',
									type: 'success'
								},
								1: {
									val: '禁用',
									type: 'danger',
									class: 'disabled'
								}
							},
							handle: 'disabled',
							no_access_show: true,
						}
					]
				},
				{
					prop: 'add_time',
					label: '添加时间',
					width: 180,
					formatter: this.filterTime
				}
			],

			operates: [
				{
					type: 'btn',
					name: '删除',
					target: 'del',
					access: ['system', 'roledel'],
					params: {
						operate: 1,
					}
				},
				{
					type: 'url',
					name: '修改',
					target: 'edit',
					access: ['system', 'roleedit'],
				},
			],
		}
	},
	created(){
		this.addScene(this.urls.list, 'default', { mapping: 'results' });
	},
	mounted(){
		this.getRequestData();
	},
	methods: {
		handle(func, ...params){
			this[func] && this[func].apply(this, params);
		},

		filter(args){
			this.getRequestData({
				args
			});
		}
    }
};
</script>

<style lang="scss">
@import "@/assets/style/table-base.scss";
</style>