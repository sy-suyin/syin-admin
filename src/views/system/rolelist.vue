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
				:urls="urls"
				:pages="pages"
				@handle="handle"
			>

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
import config from '@/assets/build/rolelist';
import tablePage from '@/components/table-page';

export default {
	name: "system_rolelist",
	components: { tablePage },
	mixins: [commonMixin, pageMixin, tableMixin],
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
</style>