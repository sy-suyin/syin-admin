<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<h2 class="page-title">角色列表</h2>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
			<db-table 
				:data="results"
				:config="config"
				:pagination="pages.default"
				@handle="handle"
			>
			</db-table>
		</div>
	</div>
</template>

<script>
import tableMixin from "@/mixins/table";
import config from "@/config/model/role/index";

export default {
	name: "role_index",
	mixins: [ tableMixin ],
  	data() {
		return {
			config,
		}
	},
	created(){
		this.addScene(config.urls.list, 'default', { mapping: 'results' });
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