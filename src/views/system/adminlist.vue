<template>
	<div class="table-base">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">管理员列表</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
			<table-filter></table-filter>

			<db-table 
				:data="results"
				:columns="columns"
				:actionbar="actionbar"
				:pagination="page_default"
				:toolbar="toolbar"
				@handle="handle"
			>
				<template #sort>
					<el-table-column label="排序" width="86">
						<template v-slot="{row}">
							<el-input v-model="row.sort" type="number" size="mini" max="99" min="0"/>
						</template>
					</el-table-column>
				</template>

				<template #roles>
					<el-table-column label="角色">
						<template slot-scope="scope">
							<el-tag class="role-tag" effect="plain" type="info" size="mini" v-for="item in scope.row.roles" :key="item.id">{{item.name}}</el-tag>
						</template>
					</el-table-column>
				</template>
			</db-table>
		</div>
	</div>
</template>

<script>
import pageMixin from "@/mixins/page";
import tableMixin from "@/mixins/table";
import commonMixin from "@/mixins/common";
import tableFilter from "@/components/table-filter";

export default {
	name: "system_adminlist",
	components: { tableFilter },
	mixins: [ commonMixin, pageMixin, tableMixin ],
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
				sort: '/system/adminsort'
			},

			columns: [
				{
					prop: 'selection',
				},
				{
					prop: 'slot',
					slot: 'sort',
					label: '排序',
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
					prop: 'disabled',
					label: '状态',
					tags: [
						{
							prop: 'is_disabled',
							class: 'disabled-btn',
							access: {
								controller: 'system',
								action: 'admindis',
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
					prop: 'slot',
					slot: 'roles',
					label: '角色',
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
					name: '删除',
					target: 'del',
					access: ['system', 'admindel'],
					params: {
						operate: 1,
					}
				},
				{
					type: 'url',
					name: '修改',
					target: 'edit',
					access: ['system', 'adminedit'],
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
					name: '添加',
					target: 'add',
					icon: "el-icon-plus",
					color: 'primary',
					access: ['system', 'adminadd'],
				},
				{
					type: 'btn',
					name: '排序',
					target: 'sort',
					icon: "el-icon-sort",
					color: 'success',
					access: ['system', 'adminsort'],
				},
				{
					type: 'url',
					name: '回收站',
					target: 'recycle',
					color: 'warning',
					icon: "el-icon-s-promotion",
					access: ['system', 'adminrecycle'],
				},
				{
					type: 'btn',
					name: '删除',
					target: 'del',
					color: 'danger',
					icon: "el-icon-delete",
					access: ['system', 'admindel'],
					params: {
						operate: 1,
					}
				},
			]
		}
	},
	created(){
		this.addScene(this.urls.list, 'default', { mapping: 'results' });
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