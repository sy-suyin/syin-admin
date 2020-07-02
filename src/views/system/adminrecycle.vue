<template>
	<div class="table-base">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">管理员回收站</h2>
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
import pageHeader from "@/components/page-header";

export default {
	name: "system_adminrecycle",
	mixins: [pageMixin, tableMixin, commonMixin],
	components: {pageHeader},
  	data() {
      	return {
			// 各跳转链接
			urls: {
				add: '/system/adminadd',
				del: '/system/admindel',
				dis: '/system/admindis',
				edit: '/system/adminedit/:id',
				list: '/system/adminlist',
				recycle: '/system/adminrecycle',
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
					label: '名称',
					width: 200,
				},
				{
					prop: 'login_name',
					label: '登录账号',
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
					access: ['system', 'admindel'],
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
					access: ['system', 'adminlist'],
				},
				{
					type: 'btn',
					name: '还原',
					target: 'del',
					color: 'success',
					icon: "el-icon-delete",
					access: ['system', 'admindel'],
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
@import "@/assets/style/table-base.scss";

.role-tag{
	margin-right: 4px;
}
</style>