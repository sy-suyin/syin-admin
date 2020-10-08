<template>
	<div class="data-table">
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

.role-tag{
	margin-right: 4px;
}
</style>