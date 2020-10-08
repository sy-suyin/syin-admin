<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">管理员列表</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
			<db-table 
				:data="results"
				:columns="columns"
				:actionbar="actionbar"
				:urls="urls"
				:pages="pages"
				@handle="handle"
			>
				<template #filter>
					<table-filter
						:fields="filter_fields"
						@handle="handle"
					></table-filter>
				</template>

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

				<template #footer>
					<table-page
						:pagination="page_default"
						@handle="handle"
					></table-page>
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
import config from '@/assets/build/adminlist';
import tablePage from '@/components/table-page';

// console.log(config);

export default {
	name: "system_adminlist",
	components: { tableFilter, tablePage },
	mixins: [ commonMixin, pageMixin, tableMixin ],
  	data() {
		return {
			urls: config.urls,
			pages: config.pages,
			columns: config.columns,
			actionbar: config.actionbar,
			filter_fields: config.filter_fields,
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
@import "@/assets/style/table.scss";

.role-tag{
	margin-right: 4px;
}
</style>